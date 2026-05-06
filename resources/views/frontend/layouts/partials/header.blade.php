    
        <header class="site-navbar py-3" role="banner">
    
                <div class="container-fluid">
                  <div class="row align-items-center">
                    
                    <div class="col-6 col-xl-2" data-aos="fade-down">
                    <h1 class="mb-0"><a href="{{ route('homepage') }}" 
                    class="text-black h2 mb-0">{{setting('site_title')}}</a></h1>
                    </div>
                    <div class="col-6 col-xl-10 text-right" data-aos="fade-down">
                      <div class="d-none d-xl-inline-block">
                        <nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
            
                          <ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
                            <li class="active"><a href="{{ route('homepage')}}">Início</a></li>
                        
                          
                            <li>
                            <a href="{{ route('service') }}">Serviços</a></li>
                            <li><a href="{{ route('about') }}">Sobre Nós</a></li>
                            <li><a href="{{ route('contact') }}">Contato</a></li>
            
                            @guest
                        <li><a href="{{ route('registerr') }}">Cadastrar-se</a></li>
                        <li><a href="{{ route('login') }}">Entrar</a></li>
                                
                            @endguest
                            @auth
                            <li><a href="{{ route('dashboard') }}">Painel</a></li>
                            <li class="has-children">
                                <a href="#">Perfil</a>
                                <ul class="dropdown">
                                  <li>
                                  <a href="{{ route('logout') }}" 
                                  onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit()">Sair</a>
                                        
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                </form>
        
                                    </li>
                                </ul>
                              </li>
                                
                            @endauth
                          </ul>
                        </nav>
                      </div>

                      <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;">
                        <a href="#" class="site-menu-toggle js-menu-toggle text-black">
                          <span class="icon-menu h3"></span>
                        </a>
                      </div>

                    </div>
          
          
                  </div>
                </div>
                
              </header>
      