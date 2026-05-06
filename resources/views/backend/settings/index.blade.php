@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">settings</li>
@endbreadcrumb


<div class="bg-white p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Todas as Configurações</h3>
        <a href="{{ route('settings.create') }}" class="btn btn-primary">Criar Nova Configuração</a>
    </div>

    <!-- Seção de Acesso Rápido -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Redes Sociais e Rodapé (Acesso Rápido)</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                @php
                    $quick_settings = [
                        'social_facebook' => ['icon' => 'facebook', 'label' => 'Facebook'],
                        'social_twitter' => ['icon' => 'twitter', 'label' => 'Twitter'],
                        'social_instagram' => ['icon' => 'instagram', 'label' => 'Instagram'],
                        'social_linkedin' => ['icon' => 'linkedin', 'label' => 'LinkedIn'],
                        'footer_description' => ['icon' => 'text-width', 'label' => 'Rodapé']
                    ];
                @endphp
                @foreach($quick_settings as $key => $data)
                    @php $s = \App\Setting::where('key', $key)->first(); @endphp
                    @if($s)
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('settings.edit', $s->slug) }}" class="btn btn-outline-primary btn-block">
                            <i class="fa fa-{{ $data['icon'] }}"></i> {{ $data['label'] }}
                        </a>
                    </div>
                    @endif
                @endforeach
            </div>
            <small class="text-muted mt-2 d-block">* Se deixar o valor como "#" ou vazio, o ícone não aparecerá no site.</small>
        </div>
    </div>

{{ photon_notification($errors)}}

@if (count($settings) > 0 )

    <table class="table table-bordered text-center mt-4">
        <tr>
            <th>ID</th>
            <th>Key</th>
            <th>Nome</th>
            <th>Value</th>
            <th>Ações</th>
        </tr>

            @foreach ($settings as $setting)
            <tr>
                    <td>
                    {{ $setting->id }}
                </td>
                <td>
                        {{ $setting->key }}
                    </td>

                    
                <td>
                    {{ $setting->display_name }}
                </td>

                <td>
                    {{ $setting->value }}
                </td>

                <td class="d-flex">
               
               
        <form action="{{ route('settings.destroy',$setting->slug) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" class="btn btn-danger" value="Del">
            </form>
            <a href="{{ route('settings.edit',$setting->slug) }}" 
                class="btn btn-success ml-3">Editar</a>

            </td>
                </tr>
                        
            @endforeach
    </table>
    @else
    
    <p class="bg-info">Nenhuma configuração encontrada ainda</p>

    @endif
</div>

@endsection
