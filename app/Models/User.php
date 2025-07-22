<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel\Concerns\HasAvatars;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasAvatars, AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'active',
    //     'active',
    //     'email',
    //     'password',
    //     'country_id',
    //     'state_id',
    //     'city_id',
    //     'address',
    //     'postal_code',
    //     'gender',
    //     'identification'
    // ];
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    protected $guarded = [];
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function calendars()
    {
        return $this->belongsToMany(Calendar::class);
    }
    public function departments()
    {
        return $this->belongsToMany(Departament::class);
    }
    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
    // User.php
    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'assigned_user_id');
    }


    // app/Models/User.php

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->image) {
            return Storage::url($this->image); // Esto depende de cómo estés manejando las imágenes
        }

        // Si no tiene imagen, devuelve la URL de las iniciales
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
