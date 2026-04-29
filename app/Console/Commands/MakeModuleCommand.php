<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name : Nome da classe base do módulo}';
    protected $description = 'Cria Controller, Service, Repository, Model, Request, Test, Migration, Factory e Seeder a partir das stubs padrão';

    private Filesystem $files;

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem();
    }

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $this->info("Gerando módulo para: $name");

        $this->generateController($name);
        $this->generateService($name);
        $this->generateRepository($name);
        $this->generateModel($name);
        $this->generateRequest($name);
        $this->generateMigration($name);
        $this->generateFactory($name);
        $this->generateSeeder($name);

        $this->call('make:genericTest', ['name' => $name . 'Test']);

        $this->info("Módulo $name gerado com sucesso!");
    }

    private function generateController(string $name)
    {
        $this->generateFromStub("controller.stub", app_path("Http/Controllers/Api/{$name}Controller.php"), $name);
        $this->info("Controller criado: {$name}Controller.php");
    }

    private function generateService(string $name)
    {
        $this->generateFromStub("service.stub", app_path("Services/{$name}Service.php"), $name);
        $this->info("Service criado: {$name}Service.php");
    }

    private function generateRepository(string $name)
    {
        $this->generateFromStub("repository.stub", app_path("Repositories/Core/{$name}Repository.php"), $name);
        $this->info("Repository criado: {$name}Repository.php");
    }

    private function generateModel(string $name)
    {
        $this->generateFromStub("model.stub", app_path("Models/{$name}.php"), $name);
        $this->info("Model criado: {$name}.php");
    }

    private function generateRequest(string $name)
    {
        $fileName = "StoreUpdate{$name}FormRequest.php";
        $this->generateFromStub("request.stub", app_path("Http/Requests/$fileName"), $name);
        $this->info("Request criado: $fileName");
    }

    private function generateMigration(string $name)
    {
        $table = Str::snake(Str::pluralStudly($name));
        $fileName = date('Y_m_d_His') . "_create_{$table}_table.php";
        $this->generateFromStub("migration.stub", database_path("migrations/$fileName"), $name, [
            '{{ table }}' => $table,
        ]);
        $this->info("Migration criada: $fileName");
    }

    private function generateFactory(string $name)
    {
        $this->generateFromStub("factory.stub", database_path("factories/{$name}Factory.php"), $name, [
            '{{ factoryNamespace }}' => 'Database\Factories',
            '{{ namespacedModel }}' => "App\\Models\\{$name}",
            '{{ factory }}' => $name,
        ]);
        $this->info("Factory criada: {$name}Factory.php");
    }

    private function generateSeeder(string $name)
    {
        $className = $name . 'Seeder';
        $this->generateFromStub("seeder.stub", database_path("seeders/{$className}.php"), $className, [
            '{{ namespace }}' => 'Database\\Seeders',
        ]);
        $this->info("Seeder criada: {$className}.php");

        $this->addSeederToDatabaseSeeder($className);
    }

    private function generateFromStub(string $stubName, string $targetPath, string $name, array $extra = [])
    {
        $stubPath = base_path("stubs/$stubName");

        if (!$this->files->exists($stubPath)) {
            $this->error("Stub não encontrado: $stubPath");
            return;
        }

        if ($this->files->exists($targetPath)) {
            $this->warn("Arquivo já existe: $targetPath");
            return;
        }

        $stubContent = $this->files->get($stubPath);
        $replacements = $this->getReplacements($targetPath, $name, $extra);
        $stubContent = str_replace(array_keys($replacements), array_values($replacements), $stubContent);

        $this->files->ensureDirectoryExists(dirname($targetPath));
        $this->files->put($targetPath, $stubContent);
    }

    private function getReplacements(string $path, string $name, array $extra = []): array
    {
        return array_merge([
            '{{ class }}' => $name,
            '{{ namespace }}' => $this->getNamespaceFromPath($path),
            '{{ modelVariable }}' => Str::camel($name),
        ], $extra);
    }

    private function getNamespaceFromPath(string $path): string
    {
        $appPath = app_path();
        $dbPath = database_path();

        if (str_starts_with($path, $appPath)) {
            $relativeDir = dirname(substr($path, strlen($appPath) + 1));
            return 'App' . ($relativeDir !== '.' ? '\\' . str_replace('/', '\\', $relativeDir) : '');
        }

        if (str_starts_with($path, $dbPath)) {
            $relativeDir = dirname(substr($path, strlen($dbPath) + 1));
            return 'Database' . ($relativeDir !== '.' ? '\\' . str_replace('/', '\\', $relativeDir) : '');
        }

        return 'App';
    }

    private function addSeederToDatabaseSeeder(string $className)
    {
        $seederFile = database_path("seeders/DatabaseSeeder.php");
        if (!$this->files->exists($seederFile))
            return;

        $content = $this->files->get($seederFile);
        $lines = explode("\n", $content);

        $useLine = "use Database\\Seeders\\$className;";

        $namespaceIndex = null;
        foreach ($lines as $i => $line) {
            if (str_starts_with(trim($line), 'namespace Database\Seeders;')) {
                $namespaceIndex = $i;
            }
            if (preg_match('/use\s+Database\\\\Seeders\\\\' . preg_quote($className, '/') . '\s*;/', $line)) {
                $useLine = null;
            }
        }

        if ($useLine && $namespaceIndex !== null) {
            array_splice($lines, $namespaceIndex + 1, 0, $useLine);
        }

        foreach ($lines as $i => $line) {
            if (str_contains($line, '$this->call([')) {
                preg_match('/\[\s*(.*?)\s*\]/s', $line, $matches);
                $existing = $matches[1] ?? '';
                if (!str_contains($existing, "$className::class")) {
                    $line = rtrim($line, ']);') . "\n            $className::class,";
                    $lines[$i] = $line;
                }
                break;
            }
        }

        $this->files->put($seederFile, implode("\n", $lines));
    }

}
