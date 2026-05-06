@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('team.index') }}">Equipe</a>
    </li>
    <li class="breadcrumb-item active">Adicionar</li>
@endbreadcrumb

<form action="{{ route('team.store') }}" method="POST" enctype="multipart/form-data" class="row">
    @csrf

    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
        <div class="bg-white p-4 shadow-sm rounded">
            <h3 class="mb-4">Adicionar Novo Membro</h3>
            {{ photon_notification($errors)}}

            <div class="form-group">
                <label for="name"><strong>Nome Completo</strong></label>
                <input type="text" class="form-control" name="name" placeholder="Ex: João da Silva" required>
            </div>
        
            <div class="form-group">
                <label for="description"><strong>Biografia / Descrição</strong></label>
                <textarea name="description" class="form-control" rows="6" placeholder="Fale um pouco sobre este membro da equipe..."></textarea>
            </div>

            <h4 class="mt-5 mb-3 border-bottom pb-2">Redes Sociais</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fab fa-instagram text-danger"></i> Instagram (URL)</label>
                        <input type="url" name="instagram" class="form-control" placeholder="https://instagram.com/perfil">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fab fa-facebook text-primary"></i> Facebook (URL)</label>
                        <input type="url" name="facebook" class="form-control" placeholder="https://facebook.com/perfil">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fab fa-twitter text-info"></i> Twitter / X (URL)</label>
                        <input type="url" name="twitter" class="form-control" placeholder="https://twitter.com/perfil">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fab fa-youtube text-danger"></i> YouTube (URL)</label>
                        <input type="url" name="youtube" class="form-control" placeholder="https://youtube.com/canal">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fab fa-whatsapp text-success"></i> WhatsApp (Número com DDD)</label>
                        <input type="text" name="whatsapp" class="form-control" placeholder="Ex: 11999999999 (apenas números)">
                        <small class="text-muted">Será gerado um link automático para conversa direta.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <div class="form-group bg-white shadow-sm p-4 rounded">
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-save"></i> Salvar Membro
            </button>
            <a href="{{ route('team.index') }}" class="btn btn-outline-secondary btn-block mt-2">Cancelar</a>
        </div>

        <div class="form-group my-4 bg-white shadow-sm p-4 rounded">
            <label><strong>Foto de Perfil</strong></label>
            <div class="border p-2 text-center mb-2 bg-light">
                <i class="fas fa-user-circle fa-5x text-muted"></i>
            </div>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control-file">
            <p class="small text-muted mt-2">Recomendado: Imagem quadrada (ex: 500x500px)</p>
        </div>
    </div>
</form>

@endsection
