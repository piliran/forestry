<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the operations associated with the operation type.
     */
    public function operation()
    {
        return $this->hasMany(Operation::class, 'operation_type_id');
    }
}
