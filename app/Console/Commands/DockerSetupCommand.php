<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DockerSetupCommand extends Command
{
    protected $signature = 'docker:setup 
                            {--force : Перезаписать существующие файлы}
                            {--with-data : Создать тестовые данные}';
    
    protected $description = 'Настройка Docker окружения с двумя БД';

    public function handle()
    {
        $this->info('🚀 Начинаем настройку Docker окружения...');

        // Создаем структуру папок
        $this->createDirectoryStructure();
        
        // Создаем Dockerfile
        $this->createDockerfile();
        
        // Создаем docker-compose.yml
        $this->createDockerCompose();
        
        // Создаем конфиги Nginx
        $this->createNginxConfig();
        
        // Создаем файлы инициализации БД
        $this->createDatabaseInitFiles();
        
        // Создаем .env.docker
        $this->createEnvDocker();

        $this->info('✅ Docker окружение настроено!');
        $this->line('');
        $this->info('📋 Команды для запуска:');
        $this->line('1. cp .env.docker .env');
        $this->line('2. docker-compose up -d --build');
        $this->line('3. docker-compose exec app php artisan key:generate');
        $this->line('4. docker-compose exec app php artisan migrate');
    }

    private function createDirectoryStructure()
    {
        $directories = [
            'docker/mysql',
            'docker/esim-mysql',
            'docker/nginx/conf.d',
            'docker/php',
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
                $this->info("📁 Создана папка: $dir");
            }
        }
    }

    private function createDockerfile()
    {
        $dockerfileContent = <<<'DOCKERFILE'
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

RUN pecl install redis && docker-php-ext-enable redis

EXPOSE 9000
CMD ["php-fpm"]
DOCKERFILE;

        $this->createFile('docker/php/Dockerfile', $dockerfileContent);
    }

    private function createDockerCompose()
    {
        $composeContent = <<<'YAML'
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: laravel_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - .:/var/www
    networks:
      - laravel_network

  webserver:
    image: nginx:alpine
    container_name: laravel_webserver
    restart: unless-stopped
    ports:
      - "80:80"
    volumes:
      - .:/var/www
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
    networks:
      - laravel_network
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    container_name: laravel_mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: laravel_db
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_PASSWORD: laravel_password
      MYSQL_USER: laravel_user
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/mysql/init.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - laravel_network

  esim-mysql:
    image: mysql:8.0
    container_name: laravel_esim_mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: esim_db
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_PASSWORD: esim_password
      MYSQL_USER: esim_user
    ports:
      - "3307:3306"
    volumes:
      - esim_mysql_data:/var/lib/mysql
      - ./docker/esim-mysql/init.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - laravel_network

  redis:
    image: redis:alpine
    container_name: laravel_redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    networks:
      - laravel_network

volumes:
  mysql_data:
  esim_mysql_data:
  redis_data:

networks:
  laravel_network:
    driver: bridge
YAML;

        $this->createFile('docker-compose.yml', $composeContent);
    }

    private function createNginxConfig()
    {
        $nginxConfig = <<<'NGINX'
server {
    listen 80;
    server_name localhost;
    root /var/www/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
}
NGINX;

        $this->createFile('docker/nginx/conf.d/app.conf', $nginxConfig);
    }

    private function createDatabaseInitFiles()
    {
        $mainDbInit = <<<'SQL'
CREATE USER IF NOT EXISTS 'laravel_user'@'%' IDENTIFIED BY 'laravel_password';
GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'%';
FLUSH PRIVILEGES;
SQL;

        $esimDbInit = <<<'SQL'
CREATE USER IF NOT EXISTS 'esim_user'@'%' IDENTIFIED BY 'esim_password';
GRANT ALL PRIVILEGES ON esim_db.* TO 'esim_user'@'%';
FLUSH PRIVILEGES;
SQL;

        $this->createFile('docker/mysql/init.sql', $mainDbInit);
        $this->createFile('docker/esim-mysql/init.sql', $esimDbInit);
    }

    private function createEnvDocker()
    {
        $envContent = <<<'ENV'
APP_NAME="Laravel eSIM Project"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password

ESIM_DB_CONNECTION=mysql
ESIM_DB_HOST=esim-mysql
ESIM_DB_PORT=3306
ESIM_DB_DATABASE=esim_db
ESIM_DB_USERNAME=esim_user
ESIM_DB_PASSWORD=esim_password

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

SENDPULSE_CLIENT_ID=your_sendpulse_id
SENDPULSE_CLIENT_SECRET=your_sendpulse_secret

API_TOKEN=your_api_token_here
ENV;

        $this->createFile('.env.docker', $envContent);
    }

    private function createFile($path, $content)
    {
        if (File::exists($path) && !$this->option('force')) {
            $this->warn("⚠️  Файл $path уже существует. Используйте --force для перезаписи.");
            return;
        }

        File::put($path, $content);
        $this->info("✅ Создан файл: $path");
    }
}
