<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable = [
        'nama',
        'jenis',
        'tanggal',
        'jumlah',
        'keterangan',
        'bukti_bayar',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
