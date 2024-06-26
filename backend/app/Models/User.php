<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime:Y-m-d H:i:s',
            'password' => 'hashed',

            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    /*
     * =================================================================================================================
     * RELATIONSHIPS
     * =================================================================================================================
     */
    public function voter() : HasOne
    {
        return $this->hasOne(Voter::class);
    }


    /*
     * =================================================================================================================
     * AUXILIARY METHODS
     * =================================================================================================================
     */
    public function deleteTokens() : void 
    {
        $this->tokens()->delete();
    }

    public function newToken(string $name = 'frontend') : string 
    {
        return $this->createToken($name)->plainTextToken;
    }

    public function regenerateToken(string $name = 'frontend')
    {
        $this->deleteTokens();
        return $this->newToken($name);
    }
}
