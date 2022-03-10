<?php

namespace App\Imports;

use App\AllCar;
use Maatwebsite\Excel\Concerns\ToModel;

class CarImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new AllCar([
            'year'     => $row[0],
            'make'    => $row[1],
            'model'    => $row[2],
        ]);
    }
}
