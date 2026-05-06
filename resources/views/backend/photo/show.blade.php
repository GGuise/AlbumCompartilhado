@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('photo.index') }}">Photo</a>
    </li>
    <li class="breadcrumb-item active">Show</li>
@endbreadcrumb


<div class="bg-white p-3">

        <h3>Detalhes do Álbum</h3>
    
    {{ photon_notification($errors) }}

    <table class="table table-bordered text-center">
        <tr>
            <th>ID</th>
            
            <td>
                    {{ $photo->id }}
                </td>
    
        </tr>

    <tr>
            <th>Título</th>
            
            <td>
                    {{ $photo->title }}
                </td>
    
        </tr>

        <tr>
                <th>Imagem</th>
                
                <td>
                        <img src="{{ asset('storage/images/'.$photo->image) }}" width="100" height="100">
                    </td>
        
            </tr>
    

            <tr>
                    <th>Descrição</th>
                    
                    <td>
                        {{ $photo->description }}
                    </td>
            
                </tr>


                <tr>
            
                <th>Ações</th>

        <td class="d-flex justify-content-center">

        <form action="{{ route('photo.destroy',$photo) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Del">
        </form>
            <a href="{{ route('photo.edit',$photo) }}" class="btn btn-success ml-3">Editar</a>

        </td>


                    </tr>
                        
    </table>

</div>
@endsection
