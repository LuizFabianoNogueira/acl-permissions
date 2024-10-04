<div class="row">
    <div class="col-12">
        <h3>ACL - Role X Permissions</h3>
    </div>

    <div class="col-12">
        <div class="card p-0">
            <div class="card-header">
                <span style="font-size: 18px;">Role - {{ $role->name }}</span>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <form action="{{ route('acl-permissions.roles.rolePermissionsStore') }}" method="post">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                    <div class="mb-3">
                        <label for="permission" class="form-label">Permission</label>
                        <ul class="list-group list-group-flush">
                            @php $module = ''; $controller = '';@endphp
                            @foreach($permissions as $permission)
                                @if($module !== $permission->module)
                                    <li class="list-group-item"><strong>{{ $permission->module }}</strong></li>
                                    @php $module = $permission->module; @endphp
                                @endif
                                @if($controller !== $permission->controller)
                                    <li class="list-group-item" style="padding-left: 50px;">
                                        <input type="checkbox" class="form-check float-start" style="margin-right: 15px;" onclick="checkPermisions('controller_{{$permission->controller}}')">
                                        <strong>{{ $permission->controller }}</strong>
                                    </li>
                                    @php $controller = $permission->controller; @endphp
                                @endif

                                <li class="list-group-item" style="padding-left: 100px;">
                                    <input
                                        type="checkbox"
                                        class="form-check controller_{{$permission->controller}} float-start"
                                        style="margin-right: 15px;"
                                        name="permissions[{{ $permission->id }}]"
                                        value="{{ $permission->id }}"
                                        @if(in_array($permission->id, $rolePermissions, true))
                                        checked="checked"
                                        @endif
                                    >
                                    <strong>[{{$permission->action}}]</strong> <i>{{ $permission->name }}</i>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function checkPermisions(controller){
        let check = document.getElementsByClassName(controller);
        for (let i = 0; i < check.length; i++) {
            check[i].checked = !check[i].checked;
        }
    }
</script>
