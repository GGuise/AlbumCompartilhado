@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('meu-album-compartilhado.index') }}">Álbuns Compartilhados</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endbreadcrumb

<form action="{{ route('meu-album-compartilhado.update', $album->slug) }}" method="POST" enctype="multipart/form-data" class="row">
    @csrf
    @method('PUT')

    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            {{ photon_notification($errors)}}
            <div class="bg-white p-3">
                <h3>Editar Álbum Compartilhado</h3>

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" value="{{ old('titulo', $album->titulo) }}" required>
                </div>

                <div class="form-group">
                    <label for="texto_personalizado">Texto Personalizado</label>
                    <textarea class="form-control" name="texto_personalizado" rows="3">{{ old('texto_personalizado', $album->texto_personalizado) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="pequena_mensagem">Pequena Mensagem (Resumo)</label>
                    <input type="text" class="form-control" name="pequena_mensagem" value="{{ old('pequena_mensagem', $album->pequena_mensagem) }}">
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="aceita_uploads" value="0">
                        <input type="checkbox" class="custom-control-input" id="aceita_uploads" name="aceita_uploads" value="1" {{ $album->aceita_uploads ? 'checked' : '' }}>
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
                        <input type="radio" id="tipo_fundo_cor" name="tipo_fundo" value="cor" class="custom-control-input" {{ ($album->tipo_fundo ?? 'cor') == 'cor' ? 'checked' : '' }} onchange="toggleFundo()">
                        <label class="custom-control-label" for="tipo_fundo_cor">Cor sólida</label>
                    </div>
                    <div class="custom-control custom-radio mt-2">
                        <input type="radio" id="tipo_fundo_imagem" name="tipo_fundo" value="imagem" class="custom-control-input" {{ $album->tipo_fundo == 'imagem' ? 'checked' : '' }} onchange="toggleFundo()">
                        <label class="custom-control-label" for="tipo_fundo_imagem">Imagem de fundo</label>
                    </div>
                </div>

                {{-- Cor de Fundo --}}
                <div class="form-group" id="campo-cor-fundo" style="{{ ($album->tipo_fundo ?? 'cor') == 'cor' ? '' : 'display:none;' }}">
                    <label>Cor de Fundo</label>
                    <div class="d-flex align-items-center">
                        <input type="color" name="cor_fundo" value="{{ $album->cor_fundo ?? '#ffffff' }}" class="mr-3" style="width: 60px; height: 40px; border: none; cursor: pointer;">
                        <input type="text" id="cor_fundo_text" value="{{ $album->cor_fundo ?? '#ffffff' }}" class="form-control" style="max-width: 120px;" onchange="document.querySelector('input[name=cor_fundo]').value=this.value">
                    </div>
                </div>

                {{-- Imagem de Fundo --}}
                <div class="form-group" id="campo-imagem-fundo" style="{{ $album->tipo_fundo == 'imagem' ? '' : 'display:none;' }}">
                    <label>Imagem de Fundo</label>
                    @if($album->imagem_fundo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/images/'.$album->imagem_fundo) }}" class="img-fluid rounded cropper-preview" style="max-height: 150px;" alt="Fundo atual">
                            <small class="d-block text-muted">Fundo atual. Envie outra para substituir.</small>
                        </div>
                    @endif
                    <input type="file" name="imagem_fundo" class="form-control-file" accept="image/*">
                </div>

                <hr>

                {{-- Fotos de Capa Responsivas --}}
                <div class="form-group">
                    <label class="font-weight-bold"><i class="fas fa-desktop"></i> Foto de Capa - Computador (Web)</label>
                    @if($album->foto_topo_web)
                        <div class="mb-2">
                            <img src="{{ asset('storage/images/'.$album->foto_topo_web) }}" class="img-fluid rounded cropper-preview" style="max-height: 120px;" alt="Capa Web">
                            <small class="d-block text-muted">Capa web atual</small>
                        </div>
                    @endif
                    <input type="file" name="foto_topo_web" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Recomendado: formato panorâmico (1920x600)</small>
                </div>

                <div class="form-group mt-4">
                    <label class="font-weight-bold"><i class="fas fa-mobile-alt"></i> Foto de Capa - Celular (Mobile)</label>
                    @if($album->foto_topo_mobile)
                        <div class="mb-2">
                            <img src="{{ asset('storage/images/'.$album->foto_topo_mobile) }}" class="img-fluid rounded cropper-preview" style="max-height: 120px;" alt="Capa Mobile">
                            <small class="d-block text-muted">Capa mobile atual</small>
                        </div>
                    @endif
                    <input type="file" name="foto_topo_mobile" class="form-control-file" accept="image/*">
                    <small class="form-text text-muted">Recomendado: formato retrato (1080x1920 ou 1080x600)</small>
                </div>
            </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="form-group bg-white p-3">
                <input type="submit" value="Atualizar" class="btn btn-primary btn-block">
            </div>
    
            <div class="form-group my-5 bg-white shadow-sm p-3">
                <label>Foto de Capa Geral</label>
                @if($album->foto_topo)
                    <div class="mb-3">
                        <img src="{{ asset('storage/images/'.$album->foto_topo) }}" class="img-fluid cropper-preview" alt="Capa atual">
                    </div>
                @endif
                <input type="file" name="foto_topo" id="thumbnail" class="form-control-file" accept="image/*">
                <small class="form-text text-muted">Deixe em branco para manter a capa atual.</small>
            </div>

            <div class="form-group my-5 bg-white shadow-sm p-3">
                <label><i class="fas fa-cloud-upload-alt text-success"></i> Google Drive</label>
                @if($album->google_drive_folder_id)
                    <div class="alert alert-success mb-0 small">
                        <i class="fas fa-check-circle"></i> <strong>Conectado!</strong><br>
                        Pasta: <a href="https://drive.google.com/drive/folders/{{ $album->google_drive_folder_id }}" target="_blank">
                            Abrir no Drive <i class="fas fa-external-link-alt"></i>
                        </a><br>
                        Todas as fotos são enviadas automaticamente para esta pasta.
                    </div>
                @else
                    <div class="alert alert-warning mb-0 small">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Sem pasta no Drive</strong><br>
                        Este álbum não tem uma pasta vinculada. As fotos são salvas apenas localmente.
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
