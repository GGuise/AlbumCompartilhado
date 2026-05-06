@extends('frontend.layouts.app')
@section('content')
    

  <div class="site-section"  data-aos="fade">
    <div class="container-fluid">
      
      <div class="row justify-content-center">
        
        <div class="col-md-7">
          <div class="row mb-5">
            <div class="col-12 ">
            <h2 class="site-section-heading text-center">{{ $album->name }}</h2>
            </div>
          </div>
        </div>
    
      </div>
      <div class="row" id="lightphoto">
         @if (count($album->photo) > 0)

            @foreach ($album->photo as $photo)
              @php 
                $ext = strtolower(pathinfo($photo->image, PATHINFO_EXTENSION));
                $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'wmv', 'webm']);
                $fileUrl = asset('storage/images/'.$photo->image);
                $placeholderUrl = asset('frontend/images/video-placeholder.png');
                $videoType = $ext == "mov" ? "mp4" : $ext;
              @endphp

            @if($isVideo)
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 item" data-aos="fade" 
                 data-thumb="{{ $placeholderUrl }}"
                 data-html="#video-p-{{ $photo->id }}"
                 data-sub-html="<h4>{{ $photo->title }}</h4><p>{{ $photo->description }}</p>">

              <div style="display:none;" id="video-p-{{ $photo->id }}">
                  <video class="lg-video-object lg-html5" controls preload="none">
                      <source src="{{ $fileUrl }}" type="video/{{ $videoType }}">
                  </video>
              </div>

              <a href="#">
                <div class="position-relative">
                  <img src="{{ $placeholderUrl }}" alt="{{ $photo->title }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.6); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: white; font-size: 24px; margin-left: 4px;">&#9654;</span>
                  </div>
                </div>
              </a>
            </div>
            @else
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 item" data-aos="fade" 
                 data-src="{{ $fileUrl }}"
                 data-thumb="{{ $fileUrl }}"
                 data-sub-html="<h4>{{ $photo->title }}</h4><p>{{ $photo->description }}</p>">
              <a href="#">
                <img src="{{ $fileUrl }}" alt="{{ $photo->title }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
              </a>
            </div>
            @endif
                      
            @endforeach
             
         @endif 

      </div>
    </div>
  </div>



@endsection
