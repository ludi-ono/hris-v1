<?php

namespace App\Models\Master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterPerusahaanModel extends Model
{
    protected $table = 'mst_perusahaan';

    use Notifiable;

    protected $fillable = [
        'id', 'kode', 'nama', 'alamat', 'logo', 'user_at', 'created_at', 'updated_at'
    ];
}
