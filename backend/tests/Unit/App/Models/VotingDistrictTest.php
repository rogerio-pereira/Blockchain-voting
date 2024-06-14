<?php

namespace Tests\Unit\App\Models;

use App\Models\VotingDistrict;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tests\Unit\App\Models\Contracts\ModelTestCase;

class VotingDistrictTest extends ModelTestCase
{
    protected function model() : Model
    {
        return new VotingDistrict();
    }

    protected function expectedTableName() : string
    {
        return 'voting_districts';
    }

    protected function expectedTraits() : array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }

    protected function expectedFillable() : array
    {
        return [
            'name',
        ];
    }

    protected function expectedHidden() : array
    {
        return [];
    }

    protected function expectedCasts() : array
    {
        return [
            ...$this->defaultCasts(),
        ];
    }
}