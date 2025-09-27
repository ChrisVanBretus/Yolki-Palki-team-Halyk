<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DockerDownCommand extends Command
{
    protected $signature = 'docker:down 
                            {--volumes : Удалить тома с данными}
                            {--clean : Полная очистка}';
    
    protected $description = 'Остановка Docker окружения';

    public function handle()
    {
        $options = '';
        
        if ($this->option('volumes')) {
            $options .= ' -v';
        }
        
        if ($this->option('clean')) {
            $options .= ' --rmi all --remove-orphans';
        }

        $this->info('🛑 Останавливаем Docker окружение...');
        exec("docker-compose down{$options}", $output, $returnCode);

        if ($returnCode === 0) {
            $this->info('✅ Docker окружение остановлено');
        } else {
            $this->error('❌ Ошибка при остановке Docker окружения');
        }
    }
}
