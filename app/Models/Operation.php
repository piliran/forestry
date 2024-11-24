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
        'description',
        'operation_type_id',
       
    ];
    /**
     * Get the operation type associated with the operation.
     */
    public function Type()
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

}
