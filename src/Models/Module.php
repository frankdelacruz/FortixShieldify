<?php

namespace Fortix\Shieldify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ModuleFactory;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];


    protected static function newFactory()
    {
        // Directly return an instance of ModuleFactory
        return ModuleFactory::new();
    }


    // Relationship to permissions
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
