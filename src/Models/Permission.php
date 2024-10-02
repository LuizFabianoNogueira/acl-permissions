<?php

namespace LuizFabianoNogueira\AclPermissions\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasUuids;

    /**
     * @var string
     */
    protected $table = 'permissions';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'module',
        'controller',
        'action'
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'id' => 'string'
        ];
    }

    public function roles(): BelongsToMany
    {
        $role = config('acl-permissions.role');
        if (!$role) {
            $role = Role::class;
        }
        if(config('acl-permissions.role_id_is') === 'UUID') {
            return $this->belongsToMany($role::class, 'permission_role', 'permission_uuid_id', 'role_uuid_id');
        }

        return $this->belongsToMany($role::class, 'permission_role', 'permission_integer_id', 'role_integer_id');
    }
}
