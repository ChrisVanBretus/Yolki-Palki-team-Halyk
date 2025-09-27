<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DockerUpCommand extends Command
{
    protected $signature = 'docker:up 
                            {--build : Пересобрать образы}
                            {--seed : Запустить сиды после миграций}';
    
    protected $description = 'Запуск Docker окружения';

    public function handle()
    {
        $this->info('🐳 Запускаем Docker окружение...');

        $build = $this->option('build') ? '--build' : '';
        
        // Проверяем установлен ли Docker
        exec('docker --version', $output, $returnCode);
        if ($returnCode !== 0) {
            $this->error('❌ Docker не установлен или не запущен!');
            return;
        }

        // Запускаем контейнеры
        $this->info('🚀 Запускаем контейнеры...');
        exec("docker-compose up -d {$build}", $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->error('❌ Ошибка при запуске Docker контейнеров');
            return;
        }

        $this->info('✅ Контейнеры запущены');
        
        // Ждем пока БД станут доступны
        $this->info('⏳ Ожидаем запуск баз данных...');
        sleep(15);

        // Генерируем ключ приложения
        $this->info('🔑 Генерируем ключ приложения...');
        exec('docker-compose exec app php artisan key:generate', $output, $returnCode);

        // Запускаем миграции
        $this->info('🔄 Запускаем миграции...');
        exec('docker-compose exec app php artisan migrate', $output, $returnCode);

        if ($this->option('seed')) {
            $this->info('🌱 Запускаем сиды...');
            exec('docker-compose exec app php artisan db:seed', $output, $returnCode);
        }

        $this->info('🎉 Docker окружение готово к работе!');
        $this->line('📊 Доступные сервисы:');
        $this->line('   - Приложение: http://localhost');
        $this->line('   - Основная БД: localhost:3306');
        $this->line('   - eSIM БД: localhost:3307');
        $this->line('   - Redis: localhost:6379');
    }
}
