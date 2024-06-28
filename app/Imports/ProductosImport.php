<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithHeadingRow
{
    protected $importedCount = 0;
    protected $data = [];

    public function model(array $row)
    {
        $this->data[] = $row;
        $this->importedCount++;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getData()
    {
        return $this->data;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
