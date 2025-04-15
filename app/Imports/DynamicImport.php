<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;
use Exception;

class DynamicImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $modelClass;
    protected $uniqueKey;
    protected $rules;
    protected $mappingConfig;
    protected $errors = [];
    protected $defaultValues;

    public function __construct(
        string $modelClass,
        string $uniqueKey,
        array $rules,
        array $mappingConfig = [],
        array $defaultValues = []
    ) {
        $this->modelClass = $modelClass;
        $this->uniqueKey = $uniqueKey;
        $this->rules = $rules;
        $this->mappingConfig = $mappingConfig;
        $this->defaultValues = $defaultValues;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = $row->toArray();
            // dd($data);
            Log::debug("Processing row " . ($index + 2) . ": " . json_encode($data));

            try {
                $mappedData = $this->mapRowToDatabaseFields($data);
                dd($mappedData);

                if (!isset($mappedData[$this->uniqueKey]) || empty($mappedData[$this->uniqueKey])) {
                    $this->errors[] = [
                        'row' => $index + 2,
                        'errors' => ["Missing or empty unique key '{$this->uniqueKey}'"],
                    ];
                    continue;
                }

                $this->modelClass::updateOrCreate(
                    [$this->uniqueKey => $mappedData[$this->uniqueKey]],
                    $mappedData
                );
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $index + 2,
                    'errors' => ["Error processing row: " . $e->getMessage()],
                ];
                Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
            }
        }

        if (!empty($this->errors)) {
            $errorMessage = 'Import failed due to the following errors: ';
            foreach ($this->errors as $error) {
                $errorMessage .= "Row {$error['row']}: " . implode(', ', $error['errors']) . '; ';
            }
            throw new Exception($errorMessage);
        }
    }

    protected function mapRowToDatabaseFields(array $data): array
    {
        $normalizedData = array_change_key_case($data, CASE_LOWER);
        $normalizedData = array_combine(
            array_map(fn($key) => str_replace([' ', '/'], '_', $key), array_keys($normalizedData)),
            array_values($normalizedData)
        );

        // Apply default values first
        $mappedData = $this->defaultValues;

        foreach ($this->mappingConfig as $dbField => $config) {
            if (is_string($config)) {
                $mappedData[$dbField] = $normalizedData[strtolower($config)] ?? null;
            } elseif (is_array($config) && isset($config['type'])) {
                switch ($config['type']) {
                    case 'foreign_key':
                        $value = $normalizedData[strtolower($config['excel_column'])] ?? null;
                        $mappedData[$dbField] = $this->resolveForeignKey(
                            $config['model'],
                            $config['lookup_field'] ?? 'name',
                            $value,
                            $dbField
                        );
                        break;
                }
            }
        }

        return $mappedData;
    }

    protected function resolveForeignKey(string $model, string $lookupField, $value, string $dbField): ?int
    {
        if (empty($value)) {
            throw new Exception("Value for '$dbField' is required.");
        }

        $record = $model::where($lookupField, $value)->first();
        if (!$record) {
            throw new Exception("No record found in '$model' for '$lookupField' = '$value'.");
        }

        return $record->id;
    }

    public function rules(): array
    {
        return $this->rules;
    }

    // Optional: Prepare data for validation by merging defaults
    public function prepareForValidation($data)
    {
        return array_merge($this->defaultValues, $data);
    }
}