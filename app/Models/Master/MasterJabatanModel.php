<?php

namespace App\Models\Master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterJabatanModel extends Model
{
    protected $table = 'mst_jabatan';

    use Notifiable;

    protected $fillable = [
        'id', 'id_perusahaan', 'id_devisi', 'nama', 'user_at', 'created_at', 'updated_at'
    ];
}
