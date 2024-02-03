<?php

namespace App\Models\Master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterUserModel extends Model
{
    protected $table = 'mst_users';

    use Notifiable;

    protected $fillable = [
        'id', 'username', 'password', 'id_karyawan', 'status', 'user_at', 'created_at', 'updated_at'
    ];
}
