<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the operations associated with the operation type.
     */
    public function operations()
    {
        return $this->hasMany(Operation::class, 'operation_type_id');
    }
}
