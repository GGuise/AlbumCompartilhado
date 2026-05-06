@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('permission.index') }}">permissions</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endbreadcrumb


    <form action="{{ route('permission.update',$permission->name) }}" method="POST" enctype="multipart/form-data" class="row">
    @csrf
    @method('PUT')

    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
        <div class="bg-white p-3">
                <h2>Editar Permissão</h2>

                {{ photon_notification($errors)}}
            
            
            <div class="form-group">
                        <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" value="{{ $permission->name }}">
                    </div>

                    <div class="form-group">
                        <label for="display_name">Nome de Exibição</label>
                    <input type="text" class="form-control" name="display_name" value="{{ $permission->display_name }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                    <input type="text" class="form-control" name="description" value="{{ $permission->description }}">
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
