<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TestCommand extends Command
{
    protected $signature = 'make:genericTest {name : Nome do arquivo de teste (ex., UserTest)}';
    protected $description = 'Gera um CRUD de testes genérico a partir da stub test-generic.stub';

    public function handle(): int
    {
        $name = Str::studly($this->argument('name')); // ex: UserTest
        $model = str_replace('Test', '', $name);      // ex: User
        $route = Str::kebab(Str::pluralStudly($model)); // ex: users
        $variable = Str::camel($model);               // ex: user
        $path = base_path("tests/Feature/Api/{$model}/{$name}.php");

        if (File::exists($path)) {
            $this->error("O arquivo {$name}.php já existe!");
            return Command::FAILURE;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->generateTestContent($model, $route, $variable));

        $this->info("Arquivo de teste criado com sucesso: tests/Feature/Api/{$model}/{$name}.php");
        return Command::SUCCESS;
    }

    private function generateTestContent(string $model, string $route, string $variable): string
    {
        $stubPath = base_path("stubs/test-generic.stub");

        if (!File::exists($stubPath)) {
            $this->error("Stub de teste não encontrada: test-generic.stub");
            return '';
        }

        $stub = File::get($stubPath);

        return str_replace(
            ['{{ model }}', '{{ route }}', '{{ variable }}'],
            [$model, $route, $variable],
            $stub
        );
    }
}
