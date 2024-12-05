<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationToFunder extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'funder_id'
    ];

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function funder()
    {
        return $this->belongsTo(Funder::class);
    }
}
