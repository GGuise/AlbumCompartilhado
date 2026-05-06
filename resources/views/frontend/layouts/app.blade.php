<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Galeria de Fotos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300i,400,700" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/magnific-popup.css')}}">
  <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">

  <link rel="stylesheet" href="{{ asset('css/lightgallery.min.css') }}">    
    
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">

  <link rel="stylesheet" href="{{ asset('fonts/flaticon/font/flaticon.css') }}">
    
  <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">

  <link rel="stylesheet" href="{{ asset('css/aos.css') }}">

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
  </head>
  <body>
  
    <div class="site-wrap">

        <div class="site-mobile-menu">
          <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
              <span class="icon-close2 js-menu-toggle"></span>
            </div>
          </div>
          <div class="site-mobile-menu-body"></div>
        </div>
        
        @include('frontend.layouts.partials.header')
    
    
    
    @yield('content')

    <footer class="footer py-5 mt-5" style="background: #111; color: #888;">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="mb-4">
              <h2 class="footer-heading mb-4 text-white">{{setting('site_title')}}</h2>
              <p>{{ setting('footer_description') ?? 'Capturando momentos, eternizando memórias.' }}</p>
            </div>
            
            <div class="mb-4">
              @if(setting('social_facebook') && setting('social_facebook') != '#')
              <a href="{{ setting('social_facebook') }}" target="_blank" class="pl-0 pr-3 text-white-50"><span class="icon-facebook"></span></a>
              @endif
              
              @if(setting('social_twitter') && setting('social_twitter') != '#')
              <a href="{{ setting('social_twitter') }}" target="_blank" class="pl-3 pr-3 text-white-50"><span class="icon-twitter"></span></a>
              @endif
              
              @if(setting('social_instagram') && setting('social_instagram') != '#')
              <a href="{{ setting('social_instagram') }}" target="_blank" class="pl-3 pr-3 text-white-50"><span class="icon-instagram"></span></a>
              @endif
              
              @if(setting('social_linkedin') && setting('social_linkedin') != '#')
              <a href="{{ setting('social_linkedin') }}" target="_blank" class="pl-3 pr-3 text-white-50"><span class="icon-linkedin"></span></a>
              @endif
            </div>

            <p class="small">
              Backend By <a href="https://github.com/gguisesoares" target="_blank" class="text-white">@ Gregory Guise Soares</a>
            </p>
            
            <p class="small text-white-50 mt-2">
              &copy; {{ date('Y') }} Todos os direitos reservados. | <a href="{{ route('visual_sitemap') }}" class="text-white-50">Mapa do Site</a>
            </p>
          </div>
        </div>
      </div>
    </footer>

    

    
    
  </div>

  <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('js/jquery-migrate-3.0.1.min.js') }}"></script>
  <script src="{{ asset('js/jquery-ui.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
  <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
  <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('js/swiper.min.js')}}"></script>
  <script src="{{ asset('js/aos.js') }}"></script>

  <script src="{{ asset('js/picturefill.min.js') }}"></script>
  <script src="{{ asset('js/lightgallery-all.min.js') }}"></script>
  <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>

  <script src="{{ asset('js/main.js') }}"></script>
  
  <script>
    $(document).ready(function(){
      $('#lightgallery').lightGallery({
        selector: '.item',
        thumbnail: true,
        animateThumb: true,
        showThumbByDefault: true
      });
      $('#lightphoto').lightGallery({
        selector: '.item',
        thumbnail: true,
        animateThumb: true,
        showThumbByDefault: true
      });

      // Auto-gerador de miniaturas para vídeos (Melhorado)
      function generateVideoThumbnails() {
        $('.item[data-html]').each(function() {
          var item = $(this);
          var videoId = item.attr('data-html').replace('#', '');
          var videoSource = $('#' + videoId).find('source').attr('src');
          var imgElement = item.find('img');
          
          if (videoSource && imgElement.length) {
            var video = document.createElement('video');
            video.src = videoSource;
            video.crossOrigin = 'anonymous';
            video.preload = 'metadata';
            video.muted = true;
            video.currentTime = 1.5; // Um pouco mais à frente para garantir imagem
            
            video.addEventListener('seeked', function() {
              var canvas = document.createElement('canvas');
              canvas.width = 640;
              canvas.height = 360;
              var ctx = canvas.getContext('2d');
              ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
              
              try {
                var dataUrl = canvas.toDataURL('image/jpeg', 0.7);
                imgElement.attr('src', dataUrl);
                item.attr('data-thumb', dataUrl);
              } catch(e) {
                console.log("Erro ao gerar thumb (CORS?):", e);
              }
            }, { once: true });
          }
        });
      }

      // Executa após um pequeno delay para garantir que o DOM e fontes estejam prontos
      setTimeout(generateVideoThumbnails, 1000);
    });
  </script>
    
  </body>
</html>