<?php

namespace App\Imports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule;

class TodosImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        return new Todo([
            'user_id' => auth()->id(),
            'title' => $row['title'],
            'description' => $row['description'] ?? $row['desc'] ?? null,
            'priority' => $row['priority'],
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'numeric',
            'description' => 'required',
        ];
    }

    public function batchSize(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1;
    }
}
