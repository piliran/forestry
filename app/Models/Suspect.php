<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suspect extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'sex',
        'national_id',
        'district_id',
        'operation_team_id',
        'village',
        'TA',
        'suspect_photo_path',
        'status',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',  // Ensures 'status' is treated as a string
    ];

    // const STATUS_UNDER_INVESTIGATION = 'Under Investigation';
    // const STATUS_ARRESTED = 'Arrested';
    // const STATUS_RELEASED_WITHOUT_CHARGE = 'Released Without Charge';
    // const STATUS_COURT_HEARING_PENDING = 'Court Hearing Pending';
    // const STATUS_ACQUITTED = 'Acquitted';
    // const STATUS_CONVICTED = 'Convicted';

    // public static $statuses = [
    //     self::STATUS_UNDER_INVESTIGATION,
    //     self::STATUS_ARRESTED,
    //     self::STATUS_RELEASED_WITHOUT_CHARGE,
    //     self::STATUS_COURT_HEARING_PENDING,
    //     self::STATUS_ACQUITTED,
    //     self::STATUS_CONVICTED,
    // ];

    const STATUS_UNDER_INVESTIGATION = 'Under Investigation';
    const STATUS_ARRESTED = 'Arrested';
    const STATUS_RELEASED_WITHOUT_CHARGE = 'Released Without Charge';
    const STATUS_COURT_HEARING_PENDING = 'Court Hearing Pending';
    const STATUS_ACQUITTED = 'Acquitted';
    const STATUS_CONVICTED = 'Convicted';


    public static function getStatuses()
    {
        return [
            self::STATUS_UNDER_INVESTIGATION,
            self::STATUS_ARRESTED,
            self::STATUS_RELEASED_WITHOUT_CHARGE,
            self::STATUS_COURT_HEARING_PENDING,
            self::STATUS_ACQUITTED,
            self::STATUS_CONVICTED,
        ];
    }


   
    /**
     * Get the district associated with the suspect.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Mutator for the suspect's photo path.
     * Converts the photo path to a full URL if stored locally.
     */
    public function getSuspectPhotoPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
