<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Verify Email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'last_login_at',
        'last_login_ip',
        'useragent',
        'email_verified_at',
        'facebook_id',
        'google_id',
        'github_id',
        'rank_id',
        'birth_date',
        'birth_place',
        'citizenship',
        'parent_father_id',
        'parent_mother_id',
        'doctor_name',
        'doctor_phone',
        'gender',
        'klas_id',
        'homeroom_klas_id',
        'nfc_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function fatherParent()
    {
        return $this->belongsTo(User::class, 'parent_father_id');
    }

    public function motherParent()
    {
        return $this->belongsTo(User::class, 'parent_mother_id');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

     // Един потребител принадлежи на един ранг
     public function rank()
     {
         return $this->belongsTo(Rank::class);
     }

    // Класът на ученика
    public function klas()
    {
        return $this->belongsTo(Klas::class, 'klas_id');
    }

    // Класът, на който учителят е класен ръководител
    public function homeroomKlas()
    {
        return $this->belongsTo(Klas::class, 'homeroom_klas_id');
    }

    // Класовете, в които участва потребителят (pivot)
    public function klasses()
    {
        return $this->belongsToMany(Klas::class, 'klas_users');
    }

    // Клубовете на потребителя
    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_user')->withTimestamps();
    }

    // Алергените на потребителя
    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'user_allergen')
                    ->withPivot('notes')
                    ->withTimestamps();
    }

    public function nfcLogs()
    {
        return $this->hasMany(NfcLog::class);
    }

}
