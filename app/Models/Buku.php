<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'item';
    protected $primaryKey = 'item_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'item_code',
        'biblio_id',
        'call_number',
    ];

    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }
}