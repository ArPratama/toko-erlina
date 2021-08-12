<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class GeneralImport implements ToCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    //use Importable;

    public function collection(Collection $rows)
    {
    }
}
