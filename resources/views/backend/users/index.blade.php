@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Usuários</li>
@endbreadcrumb



<div class="bg-white p-3">
        <h3>Todos os Usuários</h3>
        {{ photon_notification($errors)}}

    @if (count($users) > 0 )
    <table class="table table-bordered text-center">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Verificado</th>
            <th>Cargo</th>
            <th>Detalhes</th>
        </tr>

            @foreach ($users as $user)
            <tr>
                    <td>
                    {{ $user->id }}
                </td>
                <td>
                    {{ $user->name }}
                </td>
        
                <td>
                    {{ $user->email }}
                </td>

                <td>
                        {{ $user->email_verified_at ? 'verified' : 'not verified' }}
                    </td>

                    <td>
                        @if (count($user->roles)> 0)
                            @foreach ($user->roles as $role)
                                {{ $role->display_name}}
                            @endforeach
                        @else
                        <span class="text-danger">Nenhum cargo definido</span>    
                        @endif
                    </td>

                    <td>
                    <a href="{{ route('user.show',$user->id) }}" class="btn btn-success">Detalhes</a>
                    </td>
                </tr>
                        
            @endforeach
    </table>
    @else
    
    <p class="bg-info">Nenhum álbum encontrado ainda</p>

    @endif

</div>
@endsection
