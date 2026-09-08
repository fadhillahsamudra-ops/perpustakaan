<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biblio extends Model
{
    protected $table = 'biblio';
    protected $primaryKey = 'biblio_id';
    public $timestamps = false;

    protected $fillable = [
        'biblio_id',
        'title',
    ];
}