<div class="row">
    <div class="col-12">
        <h4>ACL - User X Roles</h4>
    </div>

    <div class="col-12">
        <div class="card p-0">
            <div class="card-header">
                <span style="font-size: 18px;">User X Roles Control</span>
            </div>
            <div class="card-body">

                <h5>Select access groups for the user</h5>
                <hr class="mb-3 mt-3" style="border-top: dashed 1px #999999;">
                {{ $user->name }}<br />
                {{ $user->email }}<br />
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <hr class="mb-3 mt-3" style="border-top: dashed 1px #999999;">

                <select class="form-select" id="selectUser" name="selectUser" multiple>
                    @if(count($roles)>0)
                        @foreach($roles as $role)
                            <option  {{ (in_array($role->id, $user->roles->pluck('id')->toArray(), true) ? 'selected':'') }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btn-user-role-save" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#btn-user-role-save").on("click", function () {
        let user_id = $("input[name='user_id']").val();
        let roles = $("#selectUser").val();
        $.ajax({
            url: "{{ route('acl-permissions.users.userRolesStore') }}",
            type: "POST",
            data: {
                user_id: user_id,
                roles: roles
            },
            success: function (data) {
                if (data.status === "success") {
                    alert("Roles saved successfully!");
                    location.reload();
                } else {
                    alert("Error saving roles!");
                }
            }
        });

    });
</script>
