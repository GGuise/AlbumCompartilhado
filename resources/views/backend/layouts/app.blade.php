<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Photon &mdash; Colorlib Website Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="{{ asset('backend/css/app.css') }}">
    
  </head>
  <body class="{{ request()->routeIs('dashboard') ? '' : 'sidebar-toggled' }}">
  
    <div class="site-wrap">


    @include('backend.layouts.partials.header')
    
    @include('backend.layouts.partials.sidebar')

    <div id="content-wrapper">
  
        <div class="container-fluid">

  
  
    @yield('content')


    
        
              </div>
              <!-- /.content-wrapper -->
        
            </div>
            <!-- /#wrapper -->
        
      <script src="{{ asset('backend/js/app.js') }}"></script>    
      <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>    
      <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Toggle classes on body and sidebar
                    document.body.classList.toggle('sidebar-toggled');
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar) {
                        sidebar.classList.toggle('toggled');
                    }
                });
            }
        });
      </script>
  </body>
</html>