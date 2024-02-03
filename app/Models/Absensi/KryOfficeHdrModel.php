<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KryOfficeHdrModel extends Model
{
    use HasFactory;

    protected $table = 'trx_absen_office_hdr';
    protected $fillable = [
        'id', 'file_name', 'user_at', 'created_at', 'updated_at'
    ];
}
