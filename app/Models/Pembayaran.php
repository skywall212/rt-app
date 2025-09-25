<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    // Jika nama tabel berbeda dari 'pembayarans', kamu bisa set manual:
    // protected $table = 'nama_tabel';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'warga_id',
        'tanggal',
        'jenis',
        'jumlah',
        'peserta',
        'keterangan',
        'bukti_bayar',
    ];

    // Format tanggal otomatis ke Carbon
    protected $dates = ['tanggal'];

    // Cast kolom jumlah ke float/decimal, peserta ke integer
    protected $casts = [
        'jumlah'  => 'decimal:2',
        'peserta' => 'integer',
    ];

    // Relasi ke MasterWarga
    public function warga()
    {
        return $this->belongsTo(MasterWarga::class, 'warga_id');
    }
}
