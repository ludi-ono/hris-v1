<?php

namespace App\Imports;

use App\Models\Absensi\Tmp\KryOfficeTmpModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AbsensiKryOffice implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KryOfficeTmpModel([
            'nik' => $row["nik"],
            'nama' => $row["nama"] ?? "", 
            'tanggal' => Date::excelToDateTimeObject($row["waktu"])->format('Y-m-d H:i:s'), 
            'user_at' => session()->get('sess_username'),
        ]);
        // dd(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["waktu"])->format('Y-m-d H:i:s'));
        // $data = [
        //     'nik' => $row["nik"],
        //     'nama' => $row["nama"] ?? "", 
        //     'tanggal' => Date::excelToDateTimeObject($row["waktu"])->format('Y-m-d H:i:s'),  
        //     'user_at' => session()->get('sess_username'),
        // ];
        // dd($data);
    }
}
