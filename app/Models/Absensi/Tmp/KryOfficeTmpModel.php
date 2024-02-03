<?php

namespace App\Models\Absensi\Tmp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KryOfficeTmpModel extends Model
{
    use HasFactory;
    protected $table = 'tmp_absen_office';
    protected $fillable = [
        'id', 'id_hdr', 'nik', 'nama', 'tanggal', 'status', 'keterangan', 'user_at', 'created_at', 'updated_at'
    ];
}
