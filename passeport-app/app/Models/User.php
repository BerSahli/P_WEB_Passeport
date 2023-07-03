<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Passport;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RefreshesPermissionCache;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
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

    /**
     * Represents a one-to-one relationship between the current object and the Passport class.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function passport()
    {
        return $this->hasOne(Passport::class);
    }

    /**
     * Retrives the names of the roules associated with the current object.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getRoleNames()
    {
        return $this->roles->pluck('name');
    }

    /**
     * Represents a many-to-many relationship between the current object and the Passport class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function passports()
    {
        return $this->belongsToMany(Passport::class, 'passport_has_module', 'user_id', 'passport_id')
            ->withPivot('id', 'description', 'choice', 'acronym', 'date', 'sign', 'module_id', 'created_at', 'updated_at');
    }

    /**
     * Represents a many-to-many relationship between the current object and the Module class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'passport_has_module', 'user_id', 'module_id')
            ->withPivot('id', 'description', 'choice', 'acronym', 'date', 'sign', 'passport_id', 'created_at', 'updated_at');
    }
}
