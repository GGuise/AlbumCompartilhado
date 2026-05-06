@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Autorização Google Drive</li>
@endbreadcrumb

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="bg-white p-4 shadow-sm rounded">
            @if($success)
                <div class="alert alert-success">
                    <h4><i class="fas fa-check-circle"></i> Autorização concedida com sucesso!</h4>
                    <p>O Refresh Token foi gerado. Copie o valor abaixo e adicione ao seu arquivo <code>.env</code>:</p>
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <code id="token-value" style="word-break: break-all; font-size: 0.85em;">GOOGLE_DRIVE_REFRESH_TOKEN={{ $refresh_token }}</code>
                    </div>
                </div>

                <button class="btn btn-primary" onclick="copyToken()">
                    <i class="fas fa-copy"></i> Copiar para Área de Transferência
                </button>

                <hr class="my-4">
                
                <h5>Passos finais:</h5>
                <ol>
                    <li>Abra o arquivo <code>.env</code> na raiz do projeto</li>
                    <li>Cole a linha acima no final do arquivo</li>
                    <li>Reinicie o servidor (<code>php artisan serve</code>)</li>
                    <li>Pronto! As fotos agora serão enviadas para seu Google Drive automaticamente.</li>
                </ol>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left"></i> Voltar ao Painel
                </a>
            @else
                <div class="alert alert-danger">
                    <h4><i class="fas fa-exclamation-triangle"></i> Erro na autorização</h4>
                    <p>{{ $error ?? 'Erro desconhecido.' }}</p>
                </div>
                <a href="{{ route('google.drive.auth') }}" class="btn btn-primary">Tentar Novamente</a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
            @endif
        </div>
    </div>
</div>

<script>
function copyToken() {
    var text = document.getElementById('token-value').innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert('Copiado com sucesso! Cole no seu arquivo .env');
    });
}
</script>

@endsection
