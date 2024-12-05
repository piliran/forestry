<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'station_id',
        'description',
        'date_of_operation',
        'funded_by',
        'operation_type_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_of_operation' => 'date',
    ];

    // Relationship with Station
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // Relationship with Operation Type
    public function operationType()
    {
        return $this->belongsTo(OperationType::class);
    }

    public function funder()
    {
        return $this->belongsTo(Funder::class, 'funded_by');
    }

}
