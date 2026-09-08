<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'loan';
    protected $primaryKey = 'loan_id';
    public $timestamps = false;

    protected $fillable = [
        'item_code',
        'member_id',
        'loan_date',
        'due_date',
        'is_return',
        'return_date',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'item_code', 'item_code');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'member_id', 'member_id');
    }
}