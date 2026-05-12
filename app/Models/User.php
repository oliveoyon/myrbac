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
        'full_name',
        'name',
        'email',
        'password',
        'district_id',
        'pngo_id',
        'status',
        'email_verified_at',
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

    public function pngoScopes()
    {
        return $this->hasMany(UserPngoScope::class);
    }

    public function hasPngoScopes(): bool
    {
        return $this->relationLoaded('pngoScopes')
            ? $this->pngoScopes->isNotEmpty()
            : $this->pngoScopes()->exists();
    }

    public function requiresMultiPngoScopes(): bool
    {
        if ($this->hasAnyRole(['Super Admin', 'Admin'])) {
            return false;
        }

        return $this->hasAnyRole(['M&EO', 'PNGO Focal']);
    }

    public function canAccessDistrictPngo($districtId, $pngoId): bool
    {
        if ($this->hasPngoScopes()) {
            return $this->pngoScopes()
                ->where('district_id', $districtId)
                ->where('pngo_id', $pngoId)
                ->exists();
        }

        if ($this->requiresMultiPngoScopes() && ! $this->district_id && ! $this->pngo_id) {
            return false;
        }

        if ($this->district_id && (int) $this->district_id !== (int) $districtId) {
            return false;
        }

        if ($this->pngo_id && (int) $this->pngo_id !== (int) $pngoId) {
            return false;
        }

        return true;
    }

    public function applyDistrictPngoScope($query, string $districtColumn = 'district_id', string $pngoColumn = 'pngo_id')
    {
        $scopes = $this->pngoScopes()->get(['district_id', 'pngo_id']);

        if ($scopes->isNotEmpty()) {
            return $query->where(function ($scopeQuery) use ($scopes, $districtColumn, $pngoColumn) {
                foreach ($scopes as $scope) {
                    $scopeQuery->orWhere(function ($pairQuery) use ($scope, $districtColumn, $pngoColumn) {
                        $pairQuery
                            ->where($districtColumn, $scope->district_id)
                            ->where($pngoColumn, $scope->pngo_id);
                    });
                }
            });
        }

        if ($this->requiresMultiPngoScopes() && ! $this->district_id && ! $this->pngo_id) {
            return $query->whereRaw('1 = 0');
        }

        if ($this->district_id) {
            $query->where($districtColumn, $this->district_id);
        }

        if ($this->pngo_id) {
            $query->where($pngoColumn, $this->pngo_id);
        }

        return $query;
    }

    public function accessibleDistrictIds(): ?array
    {
        $scopes = $this->pngoScopes()->get(['district_id']);

        if ($scopes->isNotEmpty()) {
            return $scopes->pluck('district_id')->unique()->values()->all();
        }

        if ($this->district_id) {
            return [(int) $this->district_id];
        }

        return $this->requiresMultiPngoScopes() ? [] : null;
    }

    public function accessiblePngoIds(): ?array
    {
        $scopes = $this->pngoScopes()->get(['pngo_id']);

        if ($scopes->isNotEmpty()) {
            return $scopes->pluck('pngo_id')->unique()->values()->all();
        }

        if ($this->pngo_id) {
            return [(int) $this->pngo_id];
        }

        return $this->requiresMultiPngoScopes() ? [] : null;
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
