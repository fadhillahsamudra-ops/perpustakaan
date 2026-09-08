<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; // Sesuai tabel user SLiMS
    protected $primaryKey = 'user_id'; // Primary key bawaan SLiMS (atau 'id')
    public $timestamps = false; // Mencegah error jika SLiMS tidak punya kolom created_at/updated_at

    protected $fillable = [
        'username',
        'realname',
        'passwd',
        'password',
        'email',
    ];

    protected $hidden = [
        'passwd',
        'password',
        'remember_token',
    ];

    // Helper agar Auth Laravel mengenali password
    public function getAuthPassword()
    {
        return $this->password ?? $this->passwd;
    }
}