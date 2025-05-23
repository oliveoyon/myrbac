<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function pngo()
    {
        return $this->belongsTo(Pngo::class);
    }

    public function getAllPermissionsList()
    {
        return [
            'direct_permissions' => $this->getDirectPermissions()->pluck('name'),
            'role_permissions' => $this->getPermissionsViaRoles()->pluck('name'),
            'all_permissions' => $this->getAllPermissions()->pluck('name'),
        ];
    }
    
}
