<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationToConfiscate extends Model
{
    //
    protected $fillable = [
        'operation_id',
        'confiscate_id'
    ];

    public function operation()
    {
        $this->belongsTo(Operation::class, 'operation_id');
    }

    public function confiscate()
    {
        $this->belongsTo(Confiscate::class, 'confiscate_id');
    }
}
