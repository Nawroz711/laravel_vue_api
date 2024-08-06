<?php

namespace App\Imports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TodosImport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
          0 =>  new FirstSheet(),
          1 =>  new SecondSheet(),
        ];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Todo([
    //         'user_id' => auth()->id(),
    //         'title' => $row['title'],
    //         'description' => $row['description'] ?? $row['desc'] ?? null,
    //         'priority' => $row['priority'],
    //     ]);
    // }
}
