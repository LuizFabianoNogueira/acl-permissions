@extends('layouts.app')

@section('content')

    <div class="container">

        @include('acl-permissions._menu')

        <div class="row justify-content-center">

            <h4>Roles</h4>
            <div class="card p-0">
                <div class="card-header">
                    <span style="font-size: 18px;">Roles Control</span>
                    <button style="float: right;" type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRoles" >Create new Role</button>
                </div>
                <div class="card-body">

                    @if(count($roles)>0)
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                @foreach($roles[0]->toArray() as $key => $line1)
                                    @if(!in_array($key, ['created_at', 'updated_at']))
                                        <th>{{ ucfirst($key) }}</th>
                                    @endif
                                @endforeach
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        @foreach($role->toArray() as $key => $line)
                                            @if(!in_array($key, ['created_at', 'updated_at']))
                                                <td>

                                                    @switch($key)
                                                        @case('id')
                                                            <span class="badge text-bg-info">{{ $line }}</span>
                                                        @break

                                                        @case('active')
                                                            @if($line)
                                                                <a href="{{ route('acl-permissions.roles.active', ['id' => $role->id]) }}" class="btn btn-sm btn-outline-success">
                                                                    <span class="badge bg-success">Active</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('acl-permissions.roles.active', ['id' => $role->id]) }}" class="btn btn-sm btn-outline-danger">
                                                                    <span class="badge bg-danger">Inactive</span>
                                                                </a>
                                                            @endif
                                                        @break

                                                        @default
                                                            {{ $line }}
                                                    @endswitch

                                                </td>
                                            @endif
                                        @endforeach
                                        <td>
                                            <button role-id="{{ $role->id }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalRoles">Edit</button>

                                            <button role-id="{{ $role->id }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalRolePermissions">Role X Permissions</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info" role="alert">
                            Roles not found
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRoles" tabindex="-1" aria-labelledby="exampleModalRoles" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalRoles">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    loading . . .
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRolePermissions" tabindex="-1" aria-labelledby="exampleModalRolePermissions" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalRolePermissions">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    loading . . .
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#modalRoles').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let roleId = button.attr('role-id');
                let modal = $(this);
                if (roleId) {
                    modal.find('.modal-title').text('Edit Role');
                } else {
                    modal.find('.modal-title').text('Create new Role');
                }
                modal.find('.modal-body').html('loading . . .');
                if (roleId) {
                    $.ajax({
                        url: '{{ route('acl-permissions.roles.edit') }}',
                        type: 'POST',
                        data:{
                            roleId: roleId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data){
                            modal.find('.modal-body').html(data);
                        }
                    });
                } else {
                    $.get('{{ route('acl-permissions.roles.create') }}', function (data) {
                        modal.find('.modal-body').html(data);
                    });
                }
            });

            $('#modalRolePermissions').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let roleId = button.attr('role-id');
                let modal = $(this);
                modal.find('.modal-title').text('Role X Permissions - Role ID: ' + roleId);
                modal.find('.modal-body').html('loading . . .');
                $.ajax({
                    url: '/acl-permissions/roles/role-x-permissions/' + roleId,
                    type: 'GET',
                    success: function(data){
                        modal.find('.modal-body').html(data);
                    }
                });
            });

        });
    </script>
@endsection
