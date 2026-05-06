@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('album.index') }}">Albums</a>
    </li>
    <li class="breadcrumb-item active">Show</li>
@endbreadcrumb


<div class="bg-white p-3">

    <h3>Detalhes do Álbum</h3>

{{ photon_notification($errors) }}
    <table class="table table-bordered text-center mt-4">
        <tr>
            <th>ID</th>
            
            <td>
                    {{ $album->id }}
                </td>
    
        </tr>

    <tr>
            <th>Nome</th>
            
            <td>
                    {{ $album->name }}
                </td>
    
        </tr>

        <tr>
                <th>Banner</th>
                
                <td>
                        <img src="{{ photon_thumbnail($album->banner) }}" width="100" height="100">
                    </td>
        
            </tr>
    
        <tr>
            
                <th>Ações</th>

        <td class="d-flex justify-content-center">

        <form action="{{ route('album.destroy',$album) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Del">
        </form>
            <a href="{{ route('album.edit',$album) }}" class="btn btn-success ml-3">Editar</a>

        </td>


                    </tr>
                        
    </table>

</div>
@endsection
