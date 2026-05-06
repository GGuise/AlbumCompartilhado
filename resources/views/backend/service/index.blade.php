@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Serviços</li>
@endbreadcrumb

<div class="bg-white p-3">
<h3>Todos os Serviços</h3>

{{ photon_notification($errors) }}

    @if (count($ourservices) > 0 )
    <table class="table table-bordered text-center">
        <tr>
            <th>ID</th>
            <th>Imagem</th>
            <th>Título</th>
            <th>Detalhes</th>
        </tr>

            @foreach ($ourservices as $service)
            <tr>
                    <td>
                    {{ $service->id }}
                </td>
                <td>
                        <img src="{{ photon_thumbnail($service->thumbnail) }}" width="50" height="50">
                        </td>
         
                <td>
                    {{ $service->title }}
                </td>

                    <td>
                    <a href="{{ route('service.show',$service->slug) }}" class="btn btn-success">Detalhes</a>
                    </td>
                </tr>
                        
            @endforeach
    </table>
    @else
    
    <p class="bg-info">Nenhuma foto encontrada ainda</p>

    @endif

</div>
@endsection
