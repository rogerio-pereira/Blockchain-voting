<?php

namespace App\Models;

use App\Models\Election;
use App\Models\Voter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VotingDistrict extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
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
    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }

    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class);
    }
}
