@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('meu-album-compartilhado.index') }}">Álbuns Compartilhados</a>
    </li>
    <li class="breadcrumb-item active">Criar</li>
@endbreadcrumb

<form action="{{ route('meu-album-compartilhado.store') }}" method="POST" enctype="multipart/form-data" class="row">
    @csrf

    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            {{ photon_notification($errors)}}
            <div class="bg-white p-3">
                <h3>Criar Álbum Compartilhado</h3>

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>

                <div class="form-group">
                    <label for="texto_personalizado">Texto Personalizado</label>
                    <textarea class="form-control" name="texto_personalizado" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="pequena_mensagem">Pequena Mensagem (Resumo)</label>
                    <input type="text" class="form-control" name="pequena_mensagem">
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="aceita_uploads" value="0">
                        <input type="checkbox" class="custom-control-input" id="aceita_uploads" name="aceita_uploads" value="1" checked>
                        <label class="custom-control-label" for="aceita_uploads">
                            <strong>Aceitar novos uploads</strong>
                            <small class="d-block text-muted">Quando desativado, ninguém poderá enviar mais fotos para este álbum.</small>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Seção de Design --}}
            <div class="bg-white p-3 mt-4">
                <h4><i class="fas fa-palette"></i> Design do Álbum</h4>
                <hr>

                {{-- Tipo de Fundo --}}
                <div class="form-group">
                    <label class="font-weight-bold">Fundo da Página</label>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="tipo_fundo_cor" name="tipo_fundo" value="cor" class="custom-control-input" checked onchange="toggleFundo()">
                        <label class="custom-control-label" for="tipo_fundo_cor">Cor sólida</label>
                    </div>
                    <div class="custom-control custom-radio mt-2">
                        <input type="radio" id="tipo_fundo_imagem" name="tipo_fundo" value="imagem" class="custom-control-input" onchange="toggleFundo()">
                        <label class="custom-control-label" for="tipo_fundo_imagem">Imagem de fundo</label>
                    </div>
                </div>

                {{-- Cor de Fundo --}}
                <div class="form-group" id="campo-cor-fundo">
                    <label>Cor de Fundo</label>
                    <div class="d-flex align-items-center">
                        <input type="color" name="cor_fundo" value="#ffffff" class="mr-3" style="width: 60px; height: 40px; border: none; cursor: pointer;">
                        <input type="text" id="cor_fundo_text" value="#ffffff" class="form-control" style="max-width: 120px;" placeholder="#ffffff" onchange="document.querySelector('input[name=cor_fundo]').value=this.value">
                    </div>
                </div>

                {{-- Imagem de Fundo --}}
                <div class="form-group" id="campo-imagem-fundo" style="display: none;">
                    <label>Imagem de Fundo</label>
                    <input type="file" name="imagem_fundo" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Recomendado: imagem de alta resolução (1920x1080+)</small>
                </div>

                <hr>

                {{-- Fotos de Capa Responsivas --}}
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-desktop"></i> Foto de Capa - Computador (Web)</label>
                    <input type="file" name="foto_topo_web" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Recomendado: formato panorâmico (1920x600 ou similar)</small>
                </div>

                <div class="form-group mt-4">
                    <label class="font-weight-bold"><i class="fas fa-mobile-alt"></i> Foto de Capa - Celular (Mobile)</label>
                    <input type="file" name="foto_topo_mobile" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Recomendado: formato retrato (1080x1920 ou 1080x600)</small>
                </div>
            </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="form-group bg-white p-3">
                <input type="submit" value="Criar" class="btn btn-primary btn-block">
            </div>
    
            <div class="form-group my-5 bg-white shadow-sm p-3">
                <label>Foto de Capa Geral (Opcional)</label>
                <input type="file" name="foto_topo" id="thumbnail" class="form-control-file" accept="image/*">
                <small class="form-text text-muted">Usada quando não houver capa web/mobile específica.</small>
            </div>

            <div class="form-group my-5 bg-white shadow-sm p-3">
                <label><i class="fas fa-cloud-upload-alt text-success"></i> Google Drive</label>
                @php
                    $driveConfigured = env('GOOGLE_DRIVE_FOLDER_ID') && env('GOOGLE_DRIVE_REFRESH_TOKEN');
                @endphp
                @if($driveConfigured)
                    <div class="alert alert-success mb-0 small">
                        <i class="fas fa-check-circle"></i> <strong>Conectado!</strong><br>
                        Uma pasta será criada automaticamente no Google Drive ao salvar este álbum. Todas as fotos enviadas serão sincronizadas.
                    </div>
                @else
                    <div class="alert alert-warning mb-0 small">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Não configurado</strong><br>
                        O arquivo de credenciais do Google Drive ainda não foi adicionado. As fotos serão salvas apenas localmente.
                    </div>
                @endif
            </div>
    </div>
</form>

@include('backend.layouts.partials.cropper')

<script>
function toggleFundo() {
    var tipoCor = document.getElementById('tipo_fundo_cor').checked;
    document.getElementById('campo-cor-fundo').style.display = tipoCor ? 'block' : 'none';
    document.getElementById('campo-imagem-fundo').style.display = tipoCor ? 'none' : 'block';
}

// Sincronizar color picker com text input
document.querySelector('input[name=cor_fundo]').addEventListener('input', function() {
    document.getElementById('cor_fundo_text').value = this.value;
});

// Inicializar Cropper para todos os inputs de imagem
document.addEventListener('DOMContentLoaded', function() {
    initCropper('input[name="foto_topo"]');
    initCropper('input[name="foto_topo_web"]');
    initCropper('input[name="foto_topo_mobile"]');
    initCropper('input[name="imagem_fundo"]');
});
</script>

@endsection
