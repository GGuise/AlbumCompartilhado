@extends('frontend.layouts.app')

@section('content')
<div class="site-section" data-aos="fade">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="site-section-heading">Mapa do Site</h2>
                <p class="lead">Encontre rapidamente todas as seções e álbuns da nossa plataforma.</p>
            </div>
        </div>

        <div class="row">
            <!-- Seção de Páginas Principais -->
            <div class="col-md-4 mb-5">
                <div class="p-4 border border-primary rounded h-100 bg-white shadow-sm">
                    <h3 class="h5 text-primary mb-4"><i class="icon-home mr-2"></i> Páginas Principais</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('homepage') }}" class="text-black">Página Inicial</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-black">Sobre Nós</a></li>
                        <li class="mb-2"><a href="{{ route('service') }}" class="text-black">Nossos Serviços</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-black">Contato</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-black">Área do Cliente (Login)</a></li>
                    </ul>
                </div>
            </div>

            <!-- Seção de Álbuns Públicos -->
            <div class="col-md-4 mb-5">
                <div class="p-4 border border-info rounded h-100 bg-white shadow-sm">
                    <h3 class="h5 text-info mb-4"><i class="icon-camera mr-2"></i> Nossos Álbuns</h3>
                    @if($albums->count() > 0)
                        <ul class="list-unstyled" style="max-height: 400px; overflow-y: auto;">
                            @foreach($albums as $album)
                                <li class="mb-2">
                                    <a href="{{ route('gallery', $album->slug) }}" class="text-black">
                                        {{ $album->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted italic small">Nenhum álbum público disponível no momento.</p>
                    @endif
                </div>
            </div>

            <!-- Seção de Álbuns Compartilhados -->
            <div class="col-md-4 mb-5">
                <div class="p-4 border border-success rounded h-100 bg-white shadow-sm">
                    <h3 class="h5 text-success mb-4"><i class="icon-users mr-2"></i> Álbuns Compartilhados</h3>
                    @if($shared_albums->count() > 0)
                        <ul class="list-unstyled" style="max-height: 400px; overflow-y: auto;">
                            @foreach($shared_albums as $shared)
                                <li class="mb-2">
                                    <a href="{{ route('shared_gallery', $shared->slug) }}" class="text-black">
                                        {{ $shared->titulo }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted italic small">Nenhum álbum compartilhado disponível no momento.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{ route('homepage') }}" class="btn btn-primary px-5 py-3">Voltar para a Home</a>
            </div>
        </div>
    </div>
</div>

<style>
    .site-section-heading {
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .site-section-heading:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: #20c997;
    }
    .list-unstyled a:hover {
        color: #20c997 !important;
        padding-left: 5px;
        transition: all 0.3s ease;
    }
</style>
@endsection
