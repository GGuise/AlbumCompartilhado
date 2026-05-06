@extends('backend.layouts.app')

@section('content')


@breadcrumb()
    <li class="breadcrumb-item active">Contact infos</li>
@endbreadcrumb

<div class="bg-white p-3">
        <h3>Todas as Informações</h3>
        {{ photon_notification($errors)}}

    @if (count($infos) > 0 )
    <table class="table table-bordered text-center">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Detalhes</th>
        </tr>

            @foreach ($infos as $info)
            <tr>
                    <td>
                    {{ $info->id }}
                </td>
        
                <td>
                    {{ $info->title }}
                </td>

                <td>
                        {{ $info->description }}
                    </td>

                    <td>
                    <a href="{{ route('contactinfo.show',$info->slug) }}" class="btn btn-success">Detalhes</a>
                    </td>
                </tr>
                        
            @endforeach
    </table>
    @else
    
    <p class="bg-info">Nenhum álbum encontrado ainda</p>

    @endif

</div>
@endsection
