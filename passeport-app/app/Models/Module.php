<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Represents a many-to-many relationship between the current object and the Passport class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function passports()
    {
        return $this->belongsToMany(Passport::class, 'passport_has_module', 'module_id', 'passport_id')
            ->withPivot('id', 'description', 'choice', 'acronym', 'date', 'sign', 'user_id', 'created_at', 'updated_at');
    }

    /**
     * Represents a many-to-many relationship between the current object and the User class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'passport_has_module', 'module_id', 'user_id')
            ->withPivot('id', 'description', 'choice', 'acronym', 'date', 'sign', 'passport_id', 'created_at', 'updated_at');
    }
}
