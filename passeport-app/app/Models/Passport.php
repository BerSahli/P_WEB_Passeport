<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Passport extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class',
        'student_choice',
        'motivation',
        'first_note',
        'second_note',
        'third_note',
        'student_date',
        'student_sign',
        'legal_comment',
        'legal_date',
        'legal_sign',
        'user_id',
        'confirmation_token',
    ];

    /**
     * Represents the relationship between the current object and the User class.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Represents a many-to-many relationship between the current object and the User class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'passport_has_module', 'passport_id', 'user_id')
            ->withPivot('id', 'description', 'choice', 'acronym', 'date', 'sign', 'module_id', 'created_at', 'updated_at');
    }

    /**
     * Represents a many-to-many relationship between the current object and the Module class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'passport_has_module', 'passport_id', 'module_id')
            ->withPivot('id', 'description', 'choice', 'acronym', 'date', 'sign', 'user_id', 'created_at', 'updated_at');
    }
}