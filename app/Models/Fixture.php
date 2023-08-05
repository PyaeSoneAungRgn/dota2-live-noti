<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_name',
        'home_team_logo',
        'home_team_win',
        'away_team_name',
        'away_team_logo',
        'away_team_win',
        'start_at',
        'tournament',
        'stage',
        'timezone',
    ];

    protected $casts = [
        'start_at' => 'datetime'
    ];
}
