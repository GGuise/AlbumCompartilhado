@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('meu-album-compartilhado.index') }}">Álbuns Compartilhados</a>
    </li>
    <li class="breadcrumb-item active">Visualizar</li>
@endbreadcrumb

<div class="row">
    <div class="col-lg-12">
        <div class="bg-white p-4">
            <h2 class="mb-4">{{ $album->titulo }}</h2>

            @if($album->foto_topo)
                <div class="mb-4">
                    <img src="{{ asset('storage/images/'.$album->foto_topo) }}" class="img-fluid rounded" alt="{{ $album->titulo }}" style="max-height: 400px; object-fit: cover; width: 100%;">
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <strong>Detalhes do Álbum Compartilhado</strong>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Pequena Mensagem:</strong> {{ $album->pequena_mensagem ?? 'Nenhuma' }}</li>
                    <li class="list-group-item"><strong>Google Drive:</strong> 
                        @if($album->google_drive_folder_id)
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Conectado</span>
                            <a href="https://drive.google.com/drive/folders/{{ $album->google_drive_folder_id }}" target="_blank" class="ml-2">
                                Abrir Pasta <i class="fas fa-external-link-alt"></i>
                            </a>
                        @else
                            <span class="badge badge-warning">Não vinculado</span>
                            <small class="text-muted ml-2">Fotos salvas apenas localmente</small>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="mb-4">
                <h5>Texto Personalizado</h5>
                <div class="p-3 bg-light border rounded">
                    {!! nl2br(e($album->texto_personalizado)) !!}
                </div>
            </div>

            <hr>
            
            <h4 class="mt-4 mb-3">Galeria de Fotos do Álbum</h4>
            
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário de Upload Múltiplo com Fila AJAX -->
            <div class="card mb-4">
                <div class="card-body">
                    <div id="backend-upload-container">
                        <div class="form-group">
                            <label><strong>Adicionar Fotos/Vídeos</strong> (Os arquivos serão enviados um por um)</label>
                            <input type="file" id="backend-fotos" name="fotos[]" class="form-control-file" multiple accept="image/*,video/*">
                        </div>
                        
                        <div id="backend-upload-status" class="mt-3" style="display: none;">
                            <div class="d-flex justify-content-between mb-1">
                                <span id="backend-status-text">Iniciando...</span>
                                <span id="backend-percent-text">0%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div id="backend-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
                            </div>
                        </div>

                        <button type="button" id="backend-start-upload" class="btn btn-success mt-2">Iniciar Upload em Fila</button>
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const startBtn = document.getElementById('backend-start-upload');
                const fileInput = document.getElementById('backend-fotos');
                const statusArea = document.getElementById('backend-upload-status');
                const progressBar = document.getElementById('backend-progress-bar');
                const statusText = document.getElementById('backend-status-text');
                const percentText = document.getElementById('backend-percent-text');

                let queue = [];
                let currentIdx = 0;

                startBtn.addEventListener('click', function() {
                    const selectedFiles = fileInput.files;
                    if (selectedFiles.length === 0) {
                        alert('Selecione pelo menos um arquivo.');
                        return;
                    }

                    queue = Array.from(selectedFiles);
                    currentIdx = 0;

                    startBtn.disabled = true;
                    fileInput.disabled = true;
                    statusArea.style.display = 'block';

                    processQueue();
                });

                function processQueue() {
                    if (currentIdx >= queue.length) {
                        statusText.innerText = 'Concluído!';
                        progressBar.style.width = '100%';
                        progressBar.classList.remove('progress-bar-animated');
                        progressBar.classList.add('bg-success');
                        setTimeout(() => { location.reload(); }, 1000);
                        return;
                    }

                    const file = queue[currentIdx];
                    statusText.innerText = `Enviando ${currentIdx + 1}/${queue.length}: ${file.name}`;
                    
                    const formData = new FormData();
                    formData.append('foto', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{ route('meu-album-compartilhado.upload_ajax', $album) }}", true);

                    xhr.upload.onprogress = function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            const globalPercent = Math.round(((currentIdx / queue.length) * 100) + (percent / queue.length));
                            progressBar.style.width = globalPercent + '%';
                            percentText.innerText = globalPercent + '%';
                        }
                    };

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            currentIdx++;
                            processQueue();
                        } else {
                            alert('Erro ao enviar: ' + file.name);
                            location.reload();
                        }
                    };

                    xhr.onerror = function() {
                        alert('Erro de rede.');
                        location.reload();
                    };

                    xhr.send(formData);
                }
            });
            </script>

            <!-- Exibição das Fotos -->
            <div class="row mt-4">
                @forelse($album->fotos as $foto)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @php $ext = pathinfo($foto->foto_path, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'wmv', 'webm']))
                                <video src="{{ asset('storage/images/'.$foto->foto_path) }}" class="card-img-top" style="height: 150px; object-fit: cover;" controls></video>
                            @else
                                <img src="{{ asset('storage/images/'.$foto->foto_path) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body p-2" style="font-size: 0.9em; background: #f8f9fa;">
                                <strong>De:</strong> {{ $foto->remetente_nome ?: 'Anônimo' }}<br>
                                @if($foto->remetente_mensagem)
                                    <em>"{{ str_limit($foto->remetente_mensagem, 50) }}"</em>
                                @endif
                            </div>
                            <div class="card-footer p-2 text-center">
                                <form action="{{ route('meu-album-compartilhado.foto.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Excluir esta foto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-block"><i class="fas fa-trash"></i> Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Nenhuma foto adicionada neste álbum ainda.</p>
                    </div>
                @endforelse
            </div>

            <hr class="my-4">

            <a href="{{ route('meu-album-compartilhado.edit', $album->slug) }}" class="btn btn-primary">Editar Álbum</a>
            <a href="{{ route('meu-album-compartilhado.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</div>

@endsection
