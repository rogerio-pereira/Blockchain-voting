<?php

namespace App\Models;

use App\Models\Candidate;

use App\Models\Vote;
use App\Models\Voter;
use App\Models\VotingDistrict;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Election extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'started',
        'ended',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'started' => 'datetime:Y-m-d H:i:s',
            'ended' => 'datetime:Y-m-d H:i:s',

            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    /*
     * =================================================================================================================
     * Query Scopes
     * =================================================================================================================
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereNotNull('started')
            ->whereNull('ended');
    }

    /*
     * =================================================================================================================
     * RELATIONSHIPS
     * =================================================================================================================
     */
    public function votingDistricts(): BelongsToMany
    {
        return $this->belongsToMany(VotingDistrict::class);
    }

    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(Voter::class);
    }

    /*
     * =================================================================================================================
     * AUXILIARY METHODS
     * =================================================================================================================
     */
    public function getResults() : array
    {
        return Vote::select([
                        'candidate_id',
                        DB::raw('count(*) as total_votes')    
                    ])
                    ->with('candidate')
                    ->where('election_id', $this->id)
                    ->groupBy('candidate_id')
                    ->orderBy('total_votes', 'DESC')
                    ->get()
                    ->toArray();
    }
}
