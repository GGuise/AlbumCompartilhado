@extends('frontend.layouts.app')
@section('content')


    <div class="container-fluid" data-aos="fade" data-aos-delay="500">
    <div class="swiper-container images-carousel">
        <div class="swiper-wrapper">
            
            @if(count($albums) > 0)
          @foreach($albums as $album)
          <div class="swiper-slide">
              <div class="image-wrap">
                <div class="image-info">
                <h2 class="mb-3">{{ $album->name }}</h2>
                <a href="{{ route('gallery',$album->slug) }}" class="btn btn-outline-white py-2 px-4">Mais Fotos</a>
                </div>
              <img src="{{ photon_thumbnail($album->banner) }}" alt="{{ $album->name }}">
              </div>
            </div>
            @endforeach
            @endif

            @if(count($shared_albums) > 0)
          @foreach($shared_albums as $shared)
          <div class="swiper-slide">
              <div class="image-wrap">
                <div class="image-info">
                <h2 class="mb-3">{{ $shared->titulo }}</h2>
                <a href="{{ route('shared_gallery',$shared->slug) }}" class="btn btn-outline-white py-2 px-4">Álbum Compartilhado</a>
                </div>
              <img src="{{ $shared->foto_topo ? asset('storage/images/'.$shared->foto_topo) : asset('frontend/images/default.jpg') }}" alt="{{ $shared->titulo }}">
              </div>
            </div>
            @endforeach
            @endif


        
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-scrollbar"></div>
    </div>
  </div>
@endsection