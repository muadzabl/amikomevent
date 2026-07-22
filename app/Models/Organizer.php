<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke User pemilik akun HIMA/UKM
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Event yang dibuat oleh HIMA ini
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}