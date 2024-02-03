<?php

namespace App\Models\Master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterDevisiModel extends Model
{
    protected $table = 'mst_devisi';

    use Notifiable;

    protected $fillable = [
        'id', 'nama', 'user_at', 'created_at', 'updated_at'
    ];
}
