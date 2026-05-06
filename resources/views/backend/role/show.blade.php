@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('role.index') }}">Cargos</a>
    </li>
    <li class="breadcrumb-item active">Show</li>
@endbreadcrumb


<div class="bg-white p-3">

    <h3>Detalhes do Cargo</h3>

{{ photon_notification($errors) }}
    <table class="table table-bordered text-center mt-4">
        <tr>
            <th>ID</th>
            
            <td>
                    {{ $role->id }}
                </td>
    
        </tr>

    <tr>
            <th>Nome</th>
            
            <td>
                    {{ $role->name }}
                </td>
    
        </tr>

        <tr>
            <th>Nome de Exibição</th>
            
            <td>
                    {{ $role->display_name }}
                </td>
    
        </tr>

        <tr>
                <th>Descrição</th>
                
                <td>
                    {{ $role->description}}
                </td>
        
            </tr>

            <tr>
                    <th>Permissões</th>
                    
                    <td>
                        
                        @if (count($role->permissions)>0)
                            @foreach($role->permissions as $permission)
                    <span>{{ $permission->display_name }}|</span>
                            @endforeach
                        @endif
                    </td>
            
                </tr>

                
        <tr>
            
                <th>Ações</th>

        <td class="d-flex justify-content-center">

        <form action="{{ route('role.destroy',$role->name) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Del">
        </form>
            <a href="{{ route('role.edit',$role->name) }}" class="btn btn-success ml-3">Editar</a>

        </td>


                    </tr>
                        
    </table>

</div>
@endsection
