<div class="row">
    <div class="col-12">
        <h3>ACL - Role Create</h3>
    </div>

    <div class="col-12">
        <div class="card p-0">
            <div class="card-header">
                <span style="font-size: 18px;">Role {{ (isset($role->id) ? 'Edit' : 'Create') }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('acl-permissions.roles.store') }}" method="post">
                    @csrf
                    @if(isset($role->id))
                        <input type="hidden" name="id" id="id" value="{{ $role->id }}">
                    @endif
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name??'' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ $role->description??'' }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
