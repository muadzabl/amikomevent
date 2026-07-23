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

    // Relasi ke Ulasan / Review via Event
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Event::class);
    }

    // Rata-rata Rating Penyelenggara
    public function averageRating()
    {
        $avg = $this->reviews()->avg('rating');
        return number_format($avg ?? 0, 1);
    }

    // Total Ulasan yang Diterima
    public function totalReviews()
    {
        return $this->reviews()->count();
    }
}