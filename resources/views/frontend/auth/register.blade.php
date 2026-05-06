@extends('frontend.layouts.app')
@section('content')


    <!-- Page Content -->
    <div class="container">

      <div class="row">
          <div class="col-8 m-auto">
            <h4 class="mb-5 mt-5 font-weight-bold">Cadastrar-se</h4>

          {{ photon_notification($errors) }}

            <form action="{{ route('registerr') }}" method="post" enctype="multipart/form-data" class="shadow p-5 bg-white mb-5">

            @csrf


            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" class="form-control" 
                placeholder="Digite o nome">
              </div>


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

              <div class="form-group">
                <label for="confirmpassword">Confirmar Senha</label>
                <input type="password" name="password_confirmation" id="confirmpassword" class="form-control" 
                aria-describedby="helpId" placeholder="Confirme sua Senha">
              </div>

              <button type="submit" class="btn btn-primary text-white text-uppercase">Cadastrar-se</button>

            </form>
          </div>
      </div>
    </div>

    @endsection
