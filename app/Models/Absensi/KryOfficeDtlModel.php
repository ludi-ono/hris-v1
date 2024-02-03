<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KryOfficeDtlModel extends Model
{
    use HasFactory;

    protected $table = 'trx_absen_office';
    protected $fillable = [
        'id', 'id_hdr', 'nik', 'nama', 'tanggal', 'status', 'keterangan', 'user_at', 'created_at', 'updated_at', 'jam_masuk', 'jam_keluar'
    ];
}
