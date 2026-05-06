@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Permissões</li>
@endbreadcrumb


<div class="bg-white p-3">
<h3>Todas as Permissões</h3>


{{ photon_notification($errors)}}

@if (count($permissions) > 0 )

    <table class="table table-bordered text-center mt-4">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Detalhes</th>
        </tr>

            @foreach ($permissions as $permission)
            <tr>
                    <td>
                    {{ $permission->id }}
                </td>

                <td>
                    {{ $permission->display_name }}
                </td>

                <td>
                    {{ $permission->description }}
                </td>

                <td>
                    <a href="{{ route('permission.show',$permission->name) }}" class="btn btn-success">Detalhes</a>
                    </td>
                </tr>
                        
            @endforeach
    </table>
    @else
    
    <p class="bg-info">Nenhuma permissão encontrada ainda</p>

    @endif
</div>

@endsection
