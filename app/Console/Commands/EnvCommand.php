<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnvCommand extends Command
{

    //Exemplo de uso: php artisan env development;
    protected $signature = 'env {env=development}';
    protected $description = 'Prepara o .env e roda o Laravel em um ambiente específico';

    public function handle()
    {
        $this->getEnvType();
        $this->generateAppKey();
        $this->clearCaches();
        $this->startServer();
    }

    private function getEnvType(): void
    {
        $env = base_path(".env");
        $envFlag = base_path(".env." . $this->argument('env'));
        $envExample = base_path('.env.example');
        $envFinal = file_exists($envFlag) ? $envFlag : $envExample;
        if (!file_exists($envFinal)) {
            $this->error("Nenhum arquivo env encontrado.");
            exit(1);
        }
        copy($envFinal, $env);
    }

    private function generateAppKey(): void
    {
        if (empty(env('APP_KEY'))) {
            $this->call('key:generate');
            $this->info("APP_KEY gerada com sucesso!");
            return;
        }

        $this->info("APP_KEY já existente, não foi gerada novamente.");
    }
    private function clearCaches(): void
    {
        $this->info("Limpando caches...");
        $this->call('optimize:clear');
    }

    private function startServer(): void
    {
        $this->info("Iniciando servidor Laravel...");
        $this->call('serve');
    }
}
