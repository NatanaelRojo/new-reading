<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:service {name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // if (!str_contains($name, '/')) {
        //     $name = 'App/Services/' . $name;
        // }

        $name = str($name)->replace('\\', '/');
        $pathParts = explode('/', $name);
        $rawClassName = array_pop($pathParts);
        $className = str($rawClassName)
            ->studly()
            ->replace(['Service', 'service'], '') . 'Service';
        $relativePath = implode('/', $pathParts);
        $fullPath = app_path("Services/{$relativePath}");
        $filePath = "{$fullPath}/{$className}.php";
        $namespace = 'App\\Services' . ($relativePath ?
            '\\' . str_replace('/', '\\', $relativePath) :
            '');

        File::ensureDirectoryExists($fullPath);

        if (File::exists($filePath)) {
            $this->error("Service {$className} already exists.");
            return self::FAILURE;
        }

        File::put(
            $filePath,
            $this->buildServiceTemplate($namespace, $className)
        );
        $this->info("✅ Service {$className} created at app/Services/{$relativePath}/");
        return self::SUCCESS;
    }

    protected function buildServiceTemplate(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

/**
 * Service for {$className}
 */
class {$className}
{
        /**
     * Get all instances from the database
     */
    public function index()
    {
    //
    }
/**
 * Store a new instance in the database
 */
    public function store()
    {
    //
    }

    /**
     * Get a single instance from the database
     */
    public function show()
    {
    //
    }

    /**
     * Update an existing instance in the database
     */
    public function update()
    {
    //
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy()
    {
    //
    }
}
PHP;
    }
}
