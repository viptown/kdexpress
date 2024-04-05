<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
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
        'password',
        'supplier_id'
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('viewAdmin', User::class);
        //   return $this->isAdmin() || $this->isEditor();
    }

    public function isAdmin(): bool
    {
        return $this->role_id === Role::where('name', 'ADMIN')->first()->id;
    }

    public function isEditor(): bool
    {
        return $this->role_id === Role::where('name', 'EDITOR')->first()->id;
    }

    public function isUser(): bool
    {
        return $this->role_id === Role::where('name', 'USER')->first()->id;
    }

    public function isApiUser(): bool
    {
        return $this->role_id === Role::where('name', 'APIUSER')->first()->id;
    }
    // public function suppliers(): HasMany
    // {
    //     return $this->hasMany(Supplier::class);
    // }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplier_orders(): HasMany
    {
        return $this->hasMany(supplier_order::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
