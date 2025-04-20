<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDtoCommand extends Command
{
    /**
     * The dtoName and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {dtoName} {--request=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Data Transfer Object using Spatie DTO';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dtoInput = str($this->argument('dtoName'))->replace('\\', '/');
        $requestOption = $this->option('request');

        // Format paths and class names
        $pathParts = explode('/', $dtoInput);
        $rawClassName = array_pop($pathParts);
        $className = str($rawClassName)->studly()->replace(['Dto', 'DTO'], '') . 'DTO';
        $relativePath = implode('/', $pathParts);
        $fullPath = app_path("DataTransferObjects/{$relativePath}");
        $filePath = "{$fullPath}/{$className}.php";
        $namespace = 'App\\DataTransferObjects' . ($relativePath ? '\\' . str_replace('/', '\\', $relativePath) : '');

        // Ensure directory
        File::ensureDirectoryExists($fullPath);

        // If file exists, skip
        if (File::exists($filePath)) {
            $this->error("DTO {$className} already exists.");
            return self::FAILURE;
        }

        $props = [];

        if ($requestOption) {
            $fullRequestClass = 'App\\Http\\Requests\\' . str($requestOption)->replace('/', '\\');
            // dd($fullRequestClass);

            if (!class_exists($fullRequestClass)) {
                $this->error("❌ Request class {$fullRequestClass} not found.");
                return self::FAILURE;
            }

            $request = app($fullRequestClass);
            $rules = method_exists($request, 'rules')
    ? $request->rules()
    : [];
            $props = $this->mapRulesToProps($rules);
        }

        File::put($filePath, $this->buildDtoTemplate($namespace, $className, $props));
        $this->info("✅ DTO {$className} created at app/DataTransferObjects/{$relativePath}/");
        return self::SUCCESS;
    }

    /**
     * Build DTO template.
     * @param string $namespace
     * @param string $className
     * @param array $props
     * @return string
     */
    protected function buildDtoTemplate(string $namespace, string $className, array $props = []): string
    {
        $baseName = str($className)->replaceLast('DTO', '');
        $propsString = collect($props)->map(
            fn (string $type, string $name): string => "        public readonly {$type} \${$name},"
        )->implode("\n");

        return <<<PHP
<?php

namespace {$namespace};

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for {$baseName}
 *
 * Auto-generated from FormRequest.
 */
class {$className} extends DataTransferObject
{
    public function __construct(
{$propsString}
    ) {}
}
PHP;
    }

    /**
     * Map rules to props.
     *
     * @param array $rules
     * @return array
     */
    protected function mapRulesToProps(array $rules): array
    {
        return collect($rules)->mapWithKeys(function (array|string $rule, string $key): array {
            $types = is_array($rule) ? $rule : explode('|', $rule);
            $type = $this->inferTypeFromRules($types);

            return [$key => $type];
        })->toArray();
    }

    /**
     * Infer type from rules array.
     *
     * @param array $rules
     * @return string
     */
    protected function inferTypeFromRules(array $rules): string
    {
        foreach ($rules as $rule) {
            return match (true) {
                $rule === 'integer' => 'int',
                $rule === 'numeric' => 'float|int',
                $rule === 'boolean' => 'bool',
                $rule === 'array' => 'array',
                $rule === 'date' => "{Carbon::class}|string",
                $rule === 'nullable' => '?string',
                default => 'string',
            };
        }

        return 'string'; // fallback
    }

}
