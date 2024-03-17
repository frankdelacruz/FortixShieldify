<?php

namespace Fortix\Shieldify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import the RoleFactory
use Database\Factories\RoleFactory;
// Ensure the User model is correctly imported
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected static function newFactory()
    {
        // Directly return an instance of RoleFactory
        return RoleFactory::new();
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }



    public function permissions()
    {
        // Ensure the correct namespace for the Permission model
        return $this->hasMany(Permission::class);
    }
}
