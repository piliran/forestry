<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',

    ];

      // Relationship with TableToPermission model
      public function tableToPermissions()
      {
          return $this->hasMany(TableToPermission::class);
      }
}
