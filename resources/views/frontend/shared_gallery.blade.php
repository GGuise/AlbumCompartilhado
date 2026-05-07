@extends('frontend.layouts.app')

@section('content')

@if($shared->foto_topo)
<div class="site-blocks-cover overlay inner-page-cover" style="background-image: url('{{ asset('storage/images/'.$shared->foto_topo) }}'); background-attachment: fixed;" data-aos="fade">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-7 text-center" data-aos="fade-up">
        <h1 class="mb-3 text-white">{{ $shared->titulo }}</h1>
        @if($shared->texto_personalizado)
            <p class="text-white opacity-75">{{ $shared->texto_personalizado }}</p>
        @endif
        
        @if($shared->aceita_uploads)
            <a href="{{ route('shared_upload_view', $shared->slug) }}" class="btn btn-primary py-3 px-5 mt-4">
                <span class="icon-plus mr-2"></span> Adicionar Minha Foto
            </a>
        @endif
      </div>
    </div>
  </div>
</div>
@else
<div class="site-section bg-light border-bottom">
  <div class="container">
    <div class="row mb-5 justify-content-center">
      <div class="col-md-7 text-center">
        <h2 class="site-section-heading text-black">{{ $shared->titulo }}</h2>
        @if($shared->texto_personalizado)
            <p class="lead text-black">{{ $shared->texto_personalizado }}</p>
        @endif
        
        @if($shared->aceita_uploads)
            <a href="{{ route('shared_upload_view', $shared->slug) }}" class="btn btn-primary py-3 px-5 mt-4">
                <span class="icon-plus mr-2"></span> Adicionar Minha Foto
            </a>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

<div class="site-section" data-aos="fade">
  <div class="container">
    
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($shared->pequena_mensagem)
        <div class="row mb-5 justify-content-center">
            <div class="col-md-8 text-center">
                <blockquote class="blockquote border-0 p-0 m-0">
                    <p class="mb-0 italic text-muted">"{{ $shared->pequena_mensagem }}"</p>
                </blockquote>
            </div>
        </div>
    @endif

    <div class="row no-gutters" id="lightgallery">
      @if($shared->fotos->count() > 0)
          @foreach($shared->fotos as $foto)
              @php
                  $is_video = false;
                  $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'webm', 'm4v'];
                  $ext = pathinfo($foto->foto_path, PATHINFO_EXTENSION);
                  $mimeType = 'video/' . strtolower($ext);
                  
                  // Normalização para formatos comuns
                  if (in_array(strtolower($ext), ['mov', 'm4v', 'quicktime'])) {
                      $mimeType = 'video/mp4';
                  }

                  if (in_array(strtolower($ext), $videoExtensions)) {
                      $is_video = true;
                  }
              @endphp

              <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 item" 
                   data-aos="fade" 
                   @if($is_video) 
                        data-video='{"source": [{"src":"{{ asset('storage/images/'.$foto->foto_path) }}", "type":"{{ $mimeType }}"}], "attributes": {"preload": false, "controls": true}}'
                        data-poster="{{ asset('frontend/images/video-placeholder.png') }}"
                        data-sub-html="<h4>Enviado por: {{ $foto->remetente_nome ?? 'Anônimo' }}</h4><p>{{ $foto->remetente_mensagem }}</p>" 
                   @else 
                        data-src="{{ asset('storage/images/'.$foto->foto_path) }}" 
                        data-sub-html="<h4>Enviado por: {{ $foto->remetente_nome ?? 'Anônimo' }}</h4><p>{{ $foto->remetente_mensagem }}</p>" 
                   @endif>
                
                <a href="#">
                    @if($is_video)
                        <div class="video-thumbnail-wrapper">
                            <video class="img-fluid" style="object-fit: cover; height: 250px; width: 100%;" preload="metadata">
                                <source src="{{ asset('storage/images/'.$foto->foto_path) }}#t=0.1" type="{{ $mimeType }}">
                            </video>
                            <div class="video-overlay">
                                <span class="icon-play-circle"></span>
                            </div>
                        </div>
                        

                    @else
                        <img src="{{ asset('storage/images/'.$foto->foto_path) }}" alt="Foto de {{ $foto->remetente_nome ?? 'Anônimo' }}" class="img-fluid" style="object-fit: cover; height: 250px; width: 100%;">
                    @endif
                </a>
              </div>
          @endforeach
      @else
          <div class="col-12 text-center py-5">
              <p class="text-muted">Ainda não há fotos neste álbum. Seja o primeiro a contribuir!</p>
              @if($shared->aceita_uploads)
                  <a href="{{ route('shared_upload_view', $shared->slug) }}" class="btn btn-outline-primary mt-3">Enviar Fotos</a>
              @endif
          </div>
      @endif
    </div>
  </div>
</div>

<style>
    .video-thumbnail-wrapper {
        position: relative;
        cursor: pointer;
    }
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }
    .video-overlay span {
        font-size: 3rem;
        color: #fff;
        opacity: 0.8;
    }
    .video-thumbnail-wrapper:hover .video-overlay {
        background: rgba(0,0,0,0.1);
    }
    .opacity-75 { opacity: 0.75; }
</style>

@endsection
