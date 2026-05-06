@extends('frontend.layouts.app')
@section('content')


    <!-- Page Content -->
    <div class="container my-5">

      <div class="row">
          <div class="col-8 m-auto">

            {{ cms_notification($errors) }}

            <h4 class="mt-3">Redefinir sua Senha</h4>


            <form action="{{ route('passwordReset') }}" method="post" class="shadow p-4 bg-white my-5">

            @csrf

            <input type="hidden" value="{{ $token }}" name="token">
              <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" 
              placeholder="Digite o E-mail" aria-describedby="helpId" value="{{ $email }}">
              </div>

              <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <input type="password" name="password" id="password" class="form-control" 
                  placeholder="Digite a nova senha" aria-describedby="helpId">
                  </div>

                  <div class="form-group">
                        <label for="cpassword">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="cpassword" class="form-control" 
                      placeholder="Confirme a senha" aria-describedby="helpId">
                      </div>
            
              <button type="submit" class="btn btn-primary">Redefinir Senha</button>
       
            </form>
          </div>
      </div>
    </div>
        @endsection
