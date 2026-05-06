@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Cargos</li>
@endbreadcrumb


<div class="bg-white p-3">
<h3>Cargos</h3>

{{ photon_notification($errors)}}

@if (count($roles) > 0 )

    <table class="table table-bordered text-center mt-4">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Detalhes</th>
        </tr>

            @foreach ($roles as $role)
            <tr>
                    <td>
                    {{ $role->id }}
                </td>

                <td>
                    {{ $role->display_name }}
                </td>

                <td>
                    {{ $role->description }}
                </td>

                <td>
                    <a href="{{ route('role.show',$role->name) }}" class="btn btn-success">Detalhes</a>
                    </td>
                </tr>
                        
            @endforeach
    </table>
    @else
    
    <p class="bg-info">Nenhum cargo encontrado ainda</p>

    @endif
</div>

@endsection
