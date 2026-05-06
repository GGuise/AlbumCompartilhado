@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Configurações de Usuário</li>
@endbreadcrumb



    <div class="bg-white p-3">
        {{ photon_notification($errors)}}
        <div class="row">
            <div class="col-md-6 col-12">
                    <h3>Detalhes do Usuário</h3>
                    <table class="table">
                        <tr>
                                <th>Nome</th>
                        <td>{{ auth()->user()->name }}</td>
                            </tr>
    
                            <tr>
                                    <th>E-mail</th>
                            <td>{{ auth()->user()->email }}</td>
                                </tr>
                    </table>
            </div>
            <div class="col-md-6 col-12">

            <form action="{{ route('profile.update') }}" method="POST" class="shadow p-4">
                @csrf
                @method('PUT')
                        <h3>Editar Configurações</h3>
                        <div class="form-group">
                      <label for="name">Nome</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ auth()->user()->name }}">
                    </div>

                    <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ auth()->user()->email }}">
                          </div>

                          <div class="form-group">
                                <label for="password">Senha</label>
                                <input type="password" class="form-control" name="password" id="password">
                              </div>

                              <div class="form-group">
                                    <label for="confirm_password">Confirmar Senha</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                                  </div>
            
                          <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Atualizar">
                              </div>
          
                </form>

            </div>
        </div>
    </div>

@endsection
