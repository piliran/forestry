<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableToPermission extends Model
{
    use HasFactory;

    protected $fillable = ['table_id', 'permission_id'];

    // Relationship with Table model
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Relationship with Permission model
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    // Relationship with Privilege model
    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }
}
