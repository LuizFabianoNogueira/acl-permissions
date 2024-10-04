@php
    $rota = Route::currentRouteName();
    $rota_arr = explode('.', $rota);
@endphp
<h3 class="mb-3">ACL - Permissions</h3>
<div class="row justify-content-center mb-3">
    <div class="col-4">
        <a href="{{ route('acl-permissions.permissions.list') }}" style="width: 100%" class="btn btn-block btn-outline-secondary {{ ($rota_arr[1] === 'permissions'? 'active': '') }}">Permissions</a>
    </div>

    <div class="col-4">
        <a href="{{ route('acl-permissions.users.list') }}" style="width: 100%" class="btn btn-block btn-outline-secondary {{ ($rota_arr[1] === 'users'? 'active': '') }}">User</a>
    </div>

    <div class="col-4">
        <a href="{{ route('acl-permissions.roles.list') }}" style="width: 100%" class="btn btn-block btn-outline-secondary {{ ($rota_arr[1] === 'roles'? 'active': '') }}">Roles</a>
    </div>
</div>
<div class="row justify-content-center mb-3">
    <hr style="border-top: dashed 1px #999999;">
</div>
