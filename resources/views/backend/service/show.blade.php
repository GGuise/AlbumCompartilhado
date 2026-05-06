@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('service.index') }}">Services</a>
    </li>
    <li class="breadcrumb-item active">Show</li>
@endbreadcrumb


<div class="container">
<h2>Detalhes do Serviço</h2>

{{ photon_notification($errors) }}
    <table class="table table-bordered text-center">
        <tr>
            <th>ID</th>
            
            <td>
                    {{ $service->id }}
                </td>
    
        </tr>

    <tr>
            <th>Título</th>
            
            <td>
                    {{ $service->title }}
                </td>
    
        </tr>

        <tr>
                <th>Imagem</th>
                
                <td>
                        <img src="{{ photon_thumbnail($service->thumbnail) }}" width="100" height="100">
                    </td>
        
            </tr>
    

            <tr>
                    <th>Descrição</th>
                    
                    <td>
                        {{ $service->description }}
                    </td>
            
                </tr>


                <tr>
            
                <th>Ações</th>

        <td class="d-flex justify-content-center">

        <form action="{{ route('service.destroy',$service->slug) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Del">
        </form>
            <a href="{{ route('service.edit',$service->slug) }}" class="btn btn-success ml-3">Editar</a>

        </td>


                    </tr>
                        
    </table>

</div>
@endsection
