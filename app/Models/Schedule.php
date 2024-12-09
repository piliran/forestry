<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;
    use HasFactory;

    //
    protected $fillable =[
        'operation_id',
        'date_of_operation',
        'deployment_time',
        'withdrawal_time'
    ];

    public function operation()
    {
        $this->belongsTo(Operation::class, 'operation_id');
    }
}
