<?php

namespace Fortix\Shieldify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\PermissionFactory;
class Permission extends Model
{

    use HasFactory;


    protected $fillable = ['role_id', 'module_id', 'permissions'];

    protected $casts = [
        'permissions' => 'array', // Cast the permissions attribute to an array
    ];


    protected static function newFactory()
    {
        // Directly return an instance of PermissionFactory
        return PermissionFactory::new();
    }


    // Relationship to roles
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relationship to modules
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
