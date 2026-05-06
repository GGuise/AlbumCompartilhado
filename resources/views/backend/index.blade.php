@extends('backend.layouts.app')

@section('content')

@breadcrumb()
@endbreadcrumb

            @php
                $user = auth()->user();
                $isAdmin = $user->hasRole('superadministrator') || $user->hasRole('administrator');
                $isReadOnly = $user->hasPermission('read-albums') && !$user->hasPermission('create-albums');
                
                if ($isAdmin || $isReadOnly) {
                    // Admin e visitantes (somente leitura) veem tudo
                    $albumCount = DB::table('albums')->count() + DB::table('meu_album_compartilhados')->count();
                    $photoCount = DB::table('photos')->count() + DB::table('meu_album_compartilhado_fotos')->count();
                    $teamCount = $isAdmin ? DB::table('teams')->count() : 0;
                    $serviceCount = $isAdmin ? DB::table('services')->count() : 0;
                } else {
                    // Criadores veem apenas seus próprios
                    $albumCount = DB::table('albums')->where('user_id', $user->id)->count() + DB::table('meu_album_compartilhados')->where('user_id', $user->id)->count();
                    $photoCount = DB::table('photos')->where('user_id', $user->id)->count();
                    $sharedAlbumIds = DB::table('meu_album_compartilhados')->where('user_id', $user->id)->pluck('id');
                    $photoCount += DB::table('meu_album_compartilhado_fotos')->whereIn('meu_album_compartilhado_id', $sharedAlbumIds)->count();
                    
                    $teamCount = 0;
                    $serviceCount = 0;
                }
            @endphp

            <!-- Icon Cards-->
            <div class="row">
              <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-primary o-hidden h-100">
                  <div class="card-body">
                    <div class="card-body-icon">
                      <i class="fas fa-fw fa-comments"></i>
                    </div>
                    <div class="mr-5">{{ $albumCount }} Álbuns!</div>
                  </div>
                <a class="card-footer text-white clearfix small z-1" href="{{ route('album.index') }}">
                    <span class="float-left">Ver Detalhes</span>
                    <span class="float-right">
                      <i class="fas fa-angle-right"></i>
                    </span>
                  </a>
                  @permission('create-albums')
                  <a class="card-footer bg-white text-primary clearfix small z-1 font-weight-bold" href="{{ route('album.create') }}">
                    <span class="float-left"><i class="fas fa-plus-circle"></i> Adicionar Álbum</span>
                    <span class="float-right">
                      <i class="fas fa-angle-right"></i>
                    </span>
                  </a>
                  @endpermission
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-warning o-hidden h-100">
                  <div class="card-body">
                    <div class="card-body-icon">
                      <i class="fas fa-fw fa-list"></i>
                    </div>
                    <div class="mr-5">{{ $photoCount }} Fotos</div>
                  </div>
                <a class="card-footer text-white clearfix small z-1" href="{{ route('photo.index') }}">
                    <span class="float-left">Ver Detalhes</span>
                    <span class="float-right">
                      <i class="fas fa-angle-right"></i>
                    </span>
                  </a>
                </div>
              </div>
              
              @if($isAdmin)
              <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-success o-hidden h-100">
                  <div class="card-body">
                    <div class="card-body-icon">
                      <i class="fas fa-fw fa-shopping-cart"></i>
                    </div>
                    <div class="mr-5">{{ $teamCount }} Membros!</div>
                  </div>
                <a class="card-footer text-white clearfix small z-1" href="{{ route('team.index') }}">
                    <span class="float-left">Ver Detalhes</span>
                    <span class="float-right">
                      <i class="fas fa-angle-right"></i>
                    </span>
                  </a>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-danger o-hidden h-100">
                  <div class="card-body">
                    <div class="card-body-icon">
                      <i class="fas fa-fw fa-life-ring"></i>
                    </div>
                    <div class="mr-5">{{ $serviceCount }} Serviços!</div>
                  </div>
                <a class="card-footer text-white clearfix small z-1" href="{{ route('service.index') }}">
                    <span class="float-left">Ver Detalhes</span>
                    <span class="float-right">
                      <i class="fas fa-angle-right"></i>
                    </span>
                  </a>
                </div>
              </div>
              @endif
            </div>

  
@endsection
