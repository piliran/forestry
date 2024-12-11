<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team1 extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'station_id',
        'created_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['deleted_at'];

    /**
     * Get the team lead for the team.
     */
    // public function teamLead()
    // {
    //     return $this->belongsTo(User::class, 'team_lead');
    // }

    /**
     * Define other relationships or methods here if needed.
     */

     public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
