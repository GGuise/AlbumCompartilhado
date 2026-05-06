
  
      <div id="wrapper">
              <!-- Sidebar -->
            <ul class="sidebar navbar-nav {{ request()->routeIs('dashboard') ? '' : 'toggled' }}">
              <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}">
                  <i class="fas fa-fw fa-tachometer-alt"></i>
                  <span>Painel</span>
                </a>
              </li>
              @permission('read-albums')

              <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownAlbums" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-fw fa-folder"></i>
                      <span>Álbuns</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesDropdownAlbums">
                    @permission('create-albums')
                    <a class="dropdown-item" href="{{ route('album.create') }}">Adicionar Álbum</a>
                    @endpermission
                    <a class="dropdown-item" href="{{ route('album.index') }}">Todos os Álbuns</a>
                    </div>
                  </li>

                  @permission('read-shared-albums')
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownShared" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-fw fa-share-alt"></i>
                      <span>Álbuns Compartilhados</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesDropdownShared">
                    @permission('create-shared-albums')
                    <a class="dropdown-item" href="{{ route('meu-album-compartilhado.create') }}">Adicionar Compartilhado</a>
                    @endpermission
                    <a class="dropdown-item" href="{{ route('meu-album-compartilhado.index') }}">Todos Compartilhados</a>
                    </div>
                  </li>
                  @endpermission

                  @endpermission

                  @permission('read-photos')
                  <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownPhotos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-fw fa-folder"></i>
                          <span>Fotos</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="pagesDropdownPhotos">
                        @permission('create-albums')
                        <a class="dropdown-item" href="{{ route('photo.create') }}">Adicionar Foto</a>
                        @endpermission
                        <a class="dropdown-item" href="{{ route('photo.index') }}">Todas as Fotos</a>
                        </div>
                      </li>
                  @endpermission
                      @role('admin|superadministrator')

                      <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownTeams" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-fw fa-folder"></i>
                              <span>Equipes</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="pagesDropdownTeams">
                            <a class="dropdown-item" href="{{ route('team.create') }}">Adicionar Equipe</a>
                            <a class="dropdown-item" href="{{ route('team.index') }}">Todas as Equipes</a>
                            </div>
                          </li>
                          
                          <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownServices" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-fw fa-folder"></i>
                                  <span>Serviços</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="pagesDropdownServices">
                                <a class="dropdown-item" href="{{ route('service.create') }}">Adicionar Serviço</a>
                                <a class="dropdown-item" href="{{ route('service.index') }}">Todos os Serviços</a>
                                </div>
                              </li>

                              <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownContact" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fas fa-fw fa-folder"></i>
                                      <span>Inf. de Contato</span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="pagesDropdownContact">
                                    <a class="dropdown-item" href="{{ route('contactinfo.create') }}">Adicionar Inf.</a>
                                    <a class="dropdown-item" href="{{ route('contactinfo.index') }}">Todas as Inf.</a>
                                    </div>
                                  </li>
                    @endrole


                    @role('superadministrator')

                    <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownPermissions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-fw fa-folder"></i>
                            <span>Permissões</span>
                          </a>
                          <div class="dropdown-menu" aria-labelledby="pagesDropdownPermissions">
                          <a class="dropdown-item" href="{{ route('permission.create') }}">Adicionar Permissão</a>
                          <a class="dropdown-item" href="{{ route('permission.index') }}">Todas Permissões</a>
                          </div>
                        </li>
                        
                            @endrole

                            @role('superadministrator')

                            <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownRoles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-folder"></i>
                                    <span>Cargos</span>
                                  </a>
                                  <div class="dropdown-menu" aria-labelledby="pagesDropdownRoles">
                                  <a class="dropdown-item" href="{{ route('role.create') }}">Adicionar Cargo</a>
                                  <a class="dropdown-item" href="{{ route('role.index') }}">Todos os Cargos</a>
                                  </div>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" 
                                    id="pagesDropdownSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fas fa-fw fa-folder"></i>
                                      <span>Configurações</span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="pagesDropdownSettings">
                                    <a class="dropdown-item" href="{{ route('settings.create') }}">Adicionar Configuração</a>
                                    <a class="dropdown-item" href="{{ route('settings.index') }}">Todas Configurações</a>
                                    </div>
                                  </li>

                                  
                                    @endrole
        
                                    @role('superadministrator')

                                    <li class="nav-item dropdown">
                                          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdownUsers" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-fw fa-folder"></i>
                                            <span>Usuários</span>
                                          </a>
                                          <div class="dropdown-menu" aria-labelledby="pagesDropdownUsers">
                                          <a class="dropdown-item" href="{{ route('user.create') }}">Adicionar Usuário</a>
                                          <a class="dropdown-item" href="{{ route('user.index') }}">Todos os Usuários</a>
                                          </div>
                                        </li>
                                        
                                            @endrole
                                  </ul>
      