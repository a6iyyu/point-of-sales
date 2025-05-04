<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticable implements JWTSubject
{
    use HasFactory;

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['username', 'password', 'nama', 'level_id', 'created_at', 'updated_at'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'user_id', 'user_id');
    }

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'user_id', 'user_id');
    }

    public function rolename(): string
    {
        return $this->level->level_nama;
    }

    public function has_role($role): bool
    {
        return $this->level->level_kode == $role;
    }

    public function get_role()
    {
        return $this->level->level_kode;
    }
}