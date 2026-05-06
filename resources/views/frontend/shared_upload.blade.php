@extends('frontend.layouts.app')
@section('content')

@php
    // Determinar o fundo
    $bgStyle = '';
    if (($shared->tipo_fundo ?? 'cor') == 'imagem' && $shared->imagem_fundo) {
        $bgStyle = "background-image: url('" . asset('storage/images/'.$shared->imagem_fundo) . "'); background-size: cover; background-position: center; background-attachment: fixed;";
    } else {
        $bgStyle = "background-color: " . ($shared->cor_fundo ?? '#ffffff') . ";";
    }
@endphp

<div class="site-section" data-aos="fade" style="{{ $bgStyle }} min-height: 100vh;">
        <div class="container-fluid">
          
          <div class="row justify-content-center">
            <div class="col-md-7">
              <div class="row mb-5">
                <div class="col-12 text-center">
                  {{-- Foto de capa responsiva --}}
                   @if($shared->foto_topo_web || $shared->foto_topo_mobile || $shared->foto_topo)
                    @if($shared->foto_topo_web)
                      <img src="{{ asset('storage/images/'.$shared->foto_topo_web) }}" alt="{{ $shared->titulo }}" class="img-fluid mb-4 d-none d-md-inline-block" style="border-radius: 10px; width: 100%; height: auto; max-height: 500px;">
                    @endif
                    @if($shared->foto_topo_mobile)
                      <img src="{{ asset('storage/images/'.$shared->foto_topo_mobile) }}" alt="{{ $shared->titulo }}" class="img-fluid mb-4 d-md-none" style="border-radius: 10px; width: 100%; height: auto; max-height: 600px;">
                    @endif
                    {{-- Fallback: se não tem web/mobile específica, usa a geral --}}
                    @if(!$shared->foto_topo_web && !$shared->foto_topo_mobile && $shared->foto_topo)
                      <img src="{{ asset('storage/images/'.$shared->foto_topo) }}" alt="{{ $shared->titulo }}" class="img-fluid mb-4" style="border-radius: 10px; width: 100%; height: auto; max-height: 500px;">
                    @endif
                    {{-- Fallback para web quando só tem geral --}}
                    @if($shared->foto_topo_web && !$shared->foto_topo_mobile && $shared->foto_topo)
                      <img src="{{ asset('storage/images/'.$shared->foto_topo) }}" alt="{{ $shared->titulo }}" class="img-fluid mb-4 d-md-none" style="border-radius: 10px; width: 100%; height: auto; max-height: 600px;">
                    @endif
                    {{-- Fallback para mobile quando só tem geral --}}
                    @if(!$shared->foto_topo_web && $shared->foto_topo_mobile && $shared->foto_topo)
                      <img src="{{ asset('storage/images/'.$shared->foto_topo) }}" alt="{{ $shared->titulo }}" class="img-fluid mb-4 d-none d-md-inline-block" style="border-radius: 10px; width: 100%; height: auto; max-height: 500px;">
                    @endif
                  @endif
                  <h2 class="site-section-heading text-center">{{ $shared->titulo }}</h2>
                  @if($shared->texto_personalizado)
                    <p class="lead mt-3">{{ $shared->texto_personalizado }}</p>
                  @endif
                </div>
              </div>
    
              <div class="row justify-content-center">
                <div class="col-lg-8 mb-5">
                  @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                  @endif

                @if(!$shared->aceita_uploads)
                <div class="p-5 bg-white shadow text-center" style="border-radius: 8px;">
                    <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Uploads Encerrados</h4>
                    <p class="text-muted">Este álbum não está aceitando novos arquivos no momento.</p>
                    <a href="{{ route('shared_gallery', $shared->slug) }}" class="btn btn-outline-primary mt-3">
                        <i class="fas fa-images"></i> Ver Galeria
                    </a>
                </div>
                @else
                <div id="upload-container" class="p-5 bg-white shadow" style="border-radius: 8px;">
                    <div class="row form-group">
                      <div class="col-md-12">
                        <label class="text-black font-weight-bold" for="nome_usuario">Seu Nome</label>
                        <input type="text" id="nome_usuario" name="nome_usuario" class="form-control" placeholder="Como devemos chamar você?">
                      </div>
                    </div>
    
                    <div class="row form-group">
                      <div class="col-md-12">
                        <label class="text-black font-weight-bold" for="mensagem">Mensagem (opcional)</label> 
                        <textarea name="mensagem" id="mensagem" cols="30" rows="3" class="form-control" placeholder="Deixe uma mensagem para o álbum..."></textarea>
                      </div>
                    </div>
    
                    <div class="row form-group">
                      <div class="col-md-12">
                        <label class="text-black font-weight-bold" for="fotos">Selecionar Arquivos (Fotos/Vídeos)</label> 
                        <input type="file" id="fotos" name="fotos[]" class="form-control-file p-2" multiple accept="image/*,video/*" style="border: 2px dashed #ccc; border-radius: 5px; background: #f9f9f9;">
                        <small class="form-text text-muted">Os arquivos serão enviados um por um para garantir o sucesso do upload.</small>
                      </div>
                    </div>

                    <div id="upload-status" class="mt-4" style="display: none;">
                        <h5 id="status-text">Iniciando upload...</h5>
                        <div class="progress" style="height: 25px;">
                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                        <div id="file-list" class="mt-3 small text-muted" style="max-height: 150px; overflow-y: auto;">
                            <!-- List of files being uploaded -->
                        </div>
                    </div>
    
                    <div class="row form-group mt-4" id="submit-area">
                      <div class="col-md-12">
                        <button id="start-upload" class="btn btn-primary py-3 px-5 text-white btn-block">Enviar Arquivos</button>
                      </div>
                    </div>
                </div>
                @endif

                </div>
              </div>
            </div>
        
          </div>
        </div>
      </div>

@if($shared->aceita_uploads)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('start-upload');
    const fileInput = document.getElementById('fotos');
    const statusArea = document.getElementById('upload-status');
    const progressBar = document.getElementById('progress-bar');
    const statusText = document.getElementById('status-text');
    const fileList = document.getElementById('file-list');
    const submitArea = document.getElementById('submit-area');

    let queue = [];
    let currentIdx = 0;

    startBtn.addEventListener('click', function() {
        const selectedFiles = fileInput.files;
        if (selectedFiles.length === 0) {
            alert('Por favor, selecione pelo menos um arquivo.');
            return;
        }

        const nome = document.getElementById('nome_usuario').value;
        const mensagem = document.getElementById('mensagem').value;

        queue = Array.from(selectedFiles);
        currentIdx = 0;

        // UI Setup
        submitArea.style.display = 'none';
        statusArea.style.display = 'block';
        fileInput.disabled = true;
        document.getElementById('nome_usuario').disabled = true;
        document.getElementById('mensagem').disabled = true;

        processQueue(nome, mensagem);
    });

    function processQueue(nome, mensagem) {
        if (currentIdx >= queue.length) {
            statusText.innerText = 'Upload Concluído!';
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-success');
            setTimeout(() => {
                window.location.href = "{{ route('shared_gallery', $shared->slug) }}?status=success";
            }, 1500);
            return;
        }

        const file = queue[currentIdx];
        statusText.innerText = `Enviando arquivo ${currentIdx + 1} de ${queue.length}...`;
        
        const formData = new FormData();
        formData.append('foto', file);
        formData.append('nome_usuario', nome);
        formData.append('mensagem', mensagem);
        formData.append('_token', '{{ csrf_token() }}');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('shared_upload_ajax', $shared->slug) }}", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                const globalPercent = Math.round(((currentIdx / queue.length) * 100) + (percentComplete / queue.length));
                progressBar.style.width = globalPercent + '%';
                progressBar.innerText = globalPercent + '%';
            }
        };

        xhr.onload = function() {
            if (xhr.status === 200) {
                const item = document.createElement('div');
                item.innerHTML = `✅ ${file.name} enviado.`;
                fileList.appendChild(item);
                fileList.scrollTop = fileList.scrollHeight;
                
                currentIdx++;
                processQueue(nome, mensagem);
            } else {
                console.error('Upload error:', xhr.status, xhr.responseText);
                const item = document.createElement('div');
                item.innerHTML = `❌ ${file.name} - Erro ${xhr.status}`;
                item.style.color = 'red';
                fileList.appendChild(item);
                statusText.innerText = `Erro no arquivo: ${file.name} (código ${xhr.status})`;
                
                // Continuar com o próximo arquivo ao invés de parar
                currentIdx++;
                processQueue(nome, mensagem);
            }
        };

        xhr.onerror = function() {
            console.error('Connection error');
            const item = document.createElement('div');
            item.innerHTML = `❌ ${file.name} - Erro de conexão`;
            item.style.color = 'red';
            fileList.appendChild(item);
            
            currentIdx++;
            processQueue(nome, mensagem);
        };

        xhr.send(formData);
    }
});
</script>
@endif
@endsection
