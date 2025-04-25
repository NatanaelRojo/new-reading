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

        $pathParts = explode('/', $dtoInput);
        $rawClassName = array_pop($pathParts);
        $className = str($rawClassName)->studly()->replace(['Dto', 'DTO'], '') . 'DTO';
        $relativePath = implode('/', $pathParts);
        $fullPath = app_path("DataTransferObjects/{$relativePath}");
        $filePath = "{$fullPath}/{$className}.php";
        $namespace = 'App\\DataTransferObjects' . ($relativePath ? '\\' . str_replace('/', '\\', $relativePath) : '');

        File::ensureDirectoryExists($fullPath);

        if (File::exists($filePath)) {
            $this->error("DTO {$className} already exists.");
            return self::FAILURE;
        }

        $props = [];

        $fullRequestClass = '';

        if ($requestOption) {
            $fullRequestClass = 'App\\Http\\Requests\\' . str($requestOption)->replace('/', '\\');

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

        File::put(
            $filePath,
            $this->buildDtoTemplate($namespace, $className, $fullRequestClass, $props)
        );
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
    protected function buildDtoTemplate(
        string $namespace,
        string $className,
        string $requestClassName,
        array $props = []
    ): string {
        $baseName = str($className)->replaceLast('DTO', '');
        $propsString = collect($props)->map(
            function (string $type, string $name): string {
                $variableType = "public readonly {$type} \${$name}";
                $defaultValue = str_starts_with($type, '?') ? ' = null,' : ',';
                return $variableType . $defaultValue;
            }
        )->implode("\n");
        $attributesArrayString = collect($props)->map(
            fn (string $type, string $name): string => "            '{$name}' => \$this->{$name},"
        )->implode("\n");


        return <<<PHP
<?php

namespace {$namespace};

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for {$baseName}
 *
 * Auto-generated from {$requestClassName}.
 */
class {$className} extends DataTransferObject
{
    public function __construct(
{$propsString}
    ) {}

    /**
     * Convert the DTO into an array.
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
{$attributesArrayString}
        ];
    }
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
        return collect($rules)->mapWithKeys(function (array|string $ruleSet, string $key): array {
            $types = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);
            $type = $this->inferTypeFromRules($types);
            // $nullable = in_array('nullable', $types) || in_array('sometimes', $types);

            // if ($nullable && !str_starts_with($type, '?')) {
            //     $type = '?' . $type;
            // }

            // return [$key => [$type, $nullable]];
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
        $nullable = in_array('nullable', $rules) || in_array('sometimes', $rules);

        $baseType = collect($rules)->first(function (string $rule) {
            return match ($rule) {
                'integer'   => true,
                'numeric'   => true,
                'boolean'   => true,
                'string'    => true,
                'array'     => true,
                'object'    => true,
                'date', 'datetime' => true,
                'email' => true,
                'url' => true,
                default     => false,
            };
        });

        $type = match ($baseType) {
            'integer'   => 'int',
            'numeric'   => 'float|int',
            'boolean'   => 'bool',
            'string'    => 'string',
            'array'     => 'array',
            'object'    => 'object',
            'date', 'datetime' => '\Carbon\Carbon|string',
            'email' => 'string',
            'url' => 'string',
            default     => 'string', // Fallback
        };

        return $nullable ? "?{$type}" : $type;
    }

}
