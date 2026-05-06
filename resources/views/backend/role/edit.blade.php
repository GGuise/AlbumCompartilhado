@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('role.index') }}">Cargos</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endbreadcrumb


    <form action="{{ route('role.update',$role->name) }}" method="POST" enctype="multipart/form-data" class="row">
    @csrf
    @method('PUT')

    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
        <div class="bg-white p-3">
                <h2>Editar Cargo</h2>

                {{ photon_notification($errors)}}
            
            
            <div class="form-group">
                        <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" value="{{ $role->name }}">
                    </div>

                    <div class="form-group">
                        <label for="display_name">Nome de Exibição</label>
                    <input type="text" class="form-control" name="display_name" value="{{ $role->display_name }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                    <input type="text" class="form-control" name="description" value="{{ $role->description }}">
                    </div>

                    <div class="form-group">
                        <label for="permission">Permissões do Cargo</label>
                        <div class="row">
                            @php
                                $groupedPermissions = $permissions->groupBy(function($perm) {
                                    $parts = explode('-', $perm->name);
                                    // Detectar módulo pelo sufixo do nome da permissão
                                    if (str_contains($perm->name, 'shared-albums')) {
                                        return 'Álbuns Compartilhados';
                                    }
                                    $module = count($parts) > 1 ? strtolower($parts[1]) : 'gerais';
                                    $titles = [
                                        'users' => 'Usuários',
                                        'roles' => 'Cargos',
                                        'albums' => 'Álbuns',
                                        'photos' => 'Fotos',
                                        'profile' => 'Perfil',
                                        'services' => 'Serviços',
                                        'gerais' => 'Gerais'
                                    ];
                                    return $titles[$module] ?? ucfirst($module);
                                });
                            @endphp

                            @foreach($groupedPermissions as $groupName => $perms)
                                <div class="col-md-4 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header bg-light font-weight-bold">
                                            {{ $groupName }}
                                        </div>
                                        <div class="card-body p-3">
                                            @foreach($perms as $permission)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission['id'],$role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_{{ $permission->id }}" style="cursor: pointer;">
                                                        {{ $permission->display_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
    
        </div>
            
    </div>    
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">

            
            <div class="form-group bg-white p-3">
                    <input type="submit" value="Atualizar" class="btn btn-primary btn-block">
                </div>

            

                        
    </div>    

    

         
    </form>

@endsection
