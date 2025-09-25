<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPengantar extends Model
{
    use HasFactory;

    protected $table = 'surat_pengantar';

    protected $fillable = [
        'master_warga_id',
        'nama',
        'nik',
        'tempat',
        'tgl_lahir',
        'jenis_kelamin',
        'status_pernikahan',
        'kewarganegaraan',
        'agama',
        'pekerjaan',
        'alamat',
        'tujuan'
    ];

    public function warga()
    {
        return $this->belongsTo(MasterWarga::class, 'master_warga_id');
    }
}
