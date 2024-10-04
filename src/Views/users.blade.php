@extends('layouts.app')

@section('content')

        <div class="container">

            @include('acl-permissions._menu')

            <div class="row justify-content-center">

                <h4>Users</h4>
                <div class="card p-0">
                    <div class="card-header">
                        Users Control
                    </div>
                    <div class="card-body">

                        <div class="bd-callout bd-callout-info p-4" style="border-left: solid 3px rgb(5, 81, 96, 05); background-color: rgb(207, 244, 252);">
                            <strong>List of users!</strong> Actions with users must be done through the structure of your system.
                        </div>

                        <hr class="mb-3 mt-3" style="border-top: dashed 1px #999999;">

                        @if(count($users)>0)
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    @foreach($users[0]->toArray() as $key => $line1)
                                        @if(!in_array($key, ['password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at']))
                                            <th>{{ ucfirst($key) }}</th>
                                        @endif
                                    @endforeach
                                    <th class="text-center">Roles</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        @foreach($user->toArray() as $key => $line)
                                            @if(!in_array($key, ['password', 'remember_token', 'created_at', 'updated_at','email_verified_at']))
                                                <td>{{ $line }}</td>
                                            @endif
                                        @endforeach
                                        <td class="text-center">
                                            @if(count($user->roles)>0)
                                                @foreach($user->roles as $role)
                                                    <span class="badge badge-primary" style="color: #4a5568;">{{ $role->name }}</span><br />
                                              @endforeach
                                            @else
                                                <span class="badge text-bg-danger">No role</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="min-width: 140px;">
                                            <button user-id="{{ $user->id }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUserRoles">User X Roles</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                    </div>
                    <div class="card-footer">
                        User Control
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalUserRoles" tabindex="-1" aria-labelledby="exampleModalUserRoles" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalUserRoles">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" id="modal-user-roles-loading">
                        loading . . .
                    </div>
                    <div class="modal-body" id="modal-user-roles-body">
                        ...
                    </div>

                </div>
            </div>
        </div>


        <script>
            $(document).ready(function(){
                $('#modalUserRoles').on('show.bs.modal', function (event) {
                    let button = $(event.relatedTarget);
                    let userId = button.attr('user-id');
                    let modal = $(this);
                    modal.find('.modal-title').text('User X Roles - User ID: ' + userId);
                    modal.find('#modal-user-roles-loading').show();
                    modal.find('#modal-user-roles-body').hide();
                    $.ajax({
                        url: '/acl-permissions/users/user-x-roles/' + userId,
                        type: 'GET',
                        success: function(data){
                            modal.find('#modal-user-roles-loading').hide();
                            modal.find('#modal-user-roles-body').html(data).show();
                        }
                    });
                });
            });
        </script>

@endsection



