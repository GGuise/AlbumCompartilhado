@extends('frontend.layouts.app')
@section('content')


    <!-- Page Content -->
    <div class="container my-5">

      <div class="row">
          <div class="col-8 m-auto">

            {{ photon_notification($errors) }}

            <h4 class="mb-5 mt-5 font-weight-bold">Entrar</h4>


            <form action="{{ route('login') }}" method="post" class="shadow p-5 bg-white my-5">

            @csrf


              <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" 
                placeholder="Digite o E-mail" aria-describedby="helpId">
              </div>

              <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-control" 
                aria-describedby="helpId" placeholder="Digite a Senha">
              </div>

            
              <div class="form-check form-check-inline w-100 mb-3">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember"> Lembrar de mim
                </label>
              </div>

              <button type="submit" class="btn btn-primary text-white text-uppercase">Entrar</button>
              
              <a href="{{ route('google.login') }}" class="btn btn-danger text-white text-uppercase ml-2">
                <i class="fab fa-google"></i> Entrar com Google
              </a>

            <a href="{{ route('passwordResetToken') }}" class="ml-2 d-block mt-3">esqueci minha senha</a>
            </form>
          </div>
      </div>
    </div>
        @endsection
