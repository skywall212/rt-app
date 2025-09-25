<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterWarga extends Model
{
    use HasFactory;

    protected $table = 'master_warga';

    protected $fillable = [
        'nama',
        'nik',
        'tempat_lahir',
        'tgl_lahir',
        'alamat'
    ];

    public function suratPengantar()
    {
        return $this->hasMany(SuratPengantar::class, 'master_warga_id');
    }
}
