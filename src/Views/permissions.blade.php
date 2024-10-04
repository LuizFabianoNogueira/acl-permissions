@extends('layouts.app')

@section('content')

        <div class="container">

            @include('acl-permissions._menu')

            <div class="row justify-content-center">

                <h4>Permissions</h4>
                <div class="card p-0">
                    <div class="card-header">
                        Permissions Control

                        <button style="float: right;" type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNewPermission">
                            New Permission
                        </button>
                    </div>
                    <div class="card-body">

                        <h4>List of Permissions</h4>

                        @if(count($permissions)>0)
                        <ul class="list-group list-group-flush">

                            @php $module = ''; @endphp
                            @foreach($permissions as $moduleName => $moduleList)

                                @if($moduleName !== $module)
                                    <li class="list-group-item">
                                        <strong>{{ $moduleName }}</strong>
                                        <span class="form-text" style="font-size: 10px;">Module</span>
                                    </li>
                                @endif

                                @php $controller = ''; @endphp
                                @foreach($moduleList as $controllerName => $controllerList)
                                    @if($controllerName !== $controller)
                                        <li class="list-group-item" style="padding-left: 50px;"><strong>{{ $controllerName }}</strong> <span class="form-text" style="font-size: 10px;">Controller</span></li>
                                    @endif

                                    @foreach($controllerList as $actionName => $id)
                                        @if(isset($routeList[$moduleName][$controllerName][$actionName]))
                                            <li class="list-group-item" style="padding-left: 100px;"><strong> {{ $actionName }}</strong></li>
                                        @else
                                                <li class="list-group-item" style="padding-left: 100px;">
                                                    <strong> {{ $actionName }} </strong>
                                                    <span class="form-text" style="font-size: 10px; color: #ff413c;">(depreciated) </span>
                                                    <form action="{{ route('acl-permissions.permissions.destroy', ['id' => $id]) }}" method="post" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                                    </form>
                                                </li>
                                        @endif


                                    @endforeach


                                    @php $controller = $controllerName; @endphp
                                @endforeach

                                @php $module = $moduleName; @endphp
                            @endforeach

                        </ul>
                        @else
                            <div class="alert alert-info" role="alert">
                                No permissions found
                            </div>
                        @endif

                        <br />
                        <br />

                        <h4>New Permissions</h4>

                        <form action="{{ route('acl-permissions.permissions.store') }}" method="post">
                            @csrf

                        @if(count($newPermissions)>0)

                            @php $nameModule = ''; @endphp
                            @foreach($newPermissions as $routeListModuleName => $routeListModule)
                                @if($routeListModuleName !== $nameModule)
                                    <div class="alert alert-dark" role="alert">
                                        <div class="form-text">
                                            Module
                                        </div>
                                       <strong> ..::|| {{ $routeListModuleName }} ||::..</strong>
                                    </div>
                                @endif

                                @if(is_array($routeListModule))
                                    @php $nameController = ''; @endphp
                                    @foreach($routeListModule as $routeListControllerName => $routeListController)
                                        @if($routeListControllerName !== $nameController)
                                            <div class="alert alert-light" role="alert" style="margin-left: 30px;">
                                                <div class="form-text">
                                                    Controller
                                                </div>
                                                <strong> ..::|| {{ $routeListControllerName }} ||::.. </strong>
                                            </div>
                                        @endif

                                        @if(is_array($routeListController))
                                            @php $nameAction = ''; @endphp
                                                <table class="table table-striped" style="margin-left: 60px; max-width: 50%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Action Name</th>
                                                            <th><input class="form-check" type="checkbox" onclick="checkPermisions('controller_{{$routeListControllerName}}')" ></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($routeListController as $routeListActionName => $routeListAction)
                                                            @if($routeListActionName !== $nameAction)
                                                                @if(!isset($permissions[$routeListModuleName][$routeListControllerName][$routeListActionName]))
                                                                <tr>
                                                                    <td>{{ $routeListActionName }}</td>
                                                                    <td><input class="form-check controller_{{$routeListControllerName}}" type="checkbox" name="permission[{{$routeListModuleName}}][{{$routeListControllerName}}][{{$routeListActionName}}]"> </td>
                                                                </tr>
                                                                @endif
                                                            @endif
                                                            @php $nameAction = $routeListActionName; @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                        @endif

                                    @endforeach
                                @endif

                                @php $nameModule = $routeListModuleName; @endphp
                            @endforeach

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Salvar selecionados</button>
                                </div>
                            </div>

                        @else
                            <div class="alert alert-info" role="alert">
                                No new permissions found
                            </div>
                        @endif
                        </form>
                    </div>

                    <div class="card-footer">
                        <div class="alert alert-warning" role="alert">
                            <strong>Attention!</strong><br />
                            The list of new permissions is done dynamically by reading the routes.<br />
                            Routes must obey the 3-level rule <code> [module, controller, action]</code>
                            using names and grouping with dot (.) in the route name.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalNewPermission" tabindex="-1" aria-labelledby="exampleModalNewPermission" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('acl-permissions.permissions.storeCustom') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalNewPermission">New custom permission</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="form-group">
                                <label for="module">Module</label>
                                <input type="text" class="form-control" id="module" name="module" required>
                            </div>
                            <div class="form-group">
                                <label for="controller">Controller</label>
                                <input type="text" class="form-control" id="controller" name="controller" required>
                            </div>
                            <div class="form-group">
                                <label for="action">Action</label>
                                <input type="text" class="form-control" id="action" name="action" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
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
@endsection
