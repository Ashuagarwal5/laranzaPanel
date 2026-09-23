<?php

namespace App\Imports;

use App\Products;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new Products([
           'qr_value'     => $row[0],
           'reward_points'    => $row[1]),
        ]);
    }
}
