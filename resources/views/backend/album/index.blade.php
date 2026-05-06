@extends('backend.layouts.app')


@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Álbuns</li>
@endbreadcrumb


<div class="bg-white p-3">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Todos os Álbuns</h3>
    @permission('create-albums')
    <a href="{{ route('album.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Adicionar Álbum
    </a>
    @endpermission
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

{{ photon_notification($errors)}}

@if (count($albums) > 0 )

    <div class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
        <div>
            <div class="custom-control custom-checkbox d-inline-block mr-3">
                <input type="checkbox" class="custom-control-input" id="select-all">
                <label class="custom-control-label" for="select-all"><strong>Selecionar Todos</strong></label>
            </div>
            <span class="text-muted">Gestão Avançada</span>
        </div>
        <div id="bulk-actions" style="display: none;">
            <button type="button" class="btn btn-sm btn-danger mr-1" id="btn-bulk-delete">
                <i class="fas fa-trash"></i> Excluir Selecionados
            </button>
            <button type="button" class="btn btn-sm btn-success mr-1" id="btn-bulk-activate">
                <i class="fas fa-lock-open"></i> Ativar Uploads
            </button>
            <button type="button" class="btn btn-sm btn-secondary" id="btn-bulk-deactivate">
                <i class="fas fa-lock"></i> Desativar Uploads
            </button>
        </div>
    </div>

    {{-- Formulários Ocultos para Ações em Massa --}}
    <form id="form-bulk-delete" action="{{ route('album.bulk_destroy') }}" method="POST" style="display:none;">
        @csrf @method('DELETE')
        <div id="delete-ids-container"></div>
    </form>
    
    <form id="form-bulk-toggle" action="{{ route('album.bulk_toggle_uploads') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="status" id="toggle-status-value">
        <div id="toggle-ids-container"></div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered text-center mt-2">
            <thead>
                <tr>
                    <th width="40"><i class="fas fa-check-square"></i></th>
                    <th>ID</th>
                    <th>Banner</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Uploads</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($albums as $album)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input album-checkbox" id="album_{{ $album->tipo_album }}_{{ $album->id }}" name="ids[]" value="{{ $album->tipo_album }}_{{ $album->id }}">
                            <label class="custom-control-label" for="album_{{ $album->tipo_album }}_{{ $album->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $album->id }}</td>
                    <td>
                        @if($album->tipo_album == 'normal')
                            <img src="{{ photon_thumbnail($album->banner) }}" width="50" height="40" style="object-fit: cover;">
                        @else
                            <img src="{{ $album->foto_topo ? asset('storage/images/'.$album->foto_topo) : asset('frontend/images/default.jpg') }}" width="50" height="40" style="object-fit: cover;">
                        @endif
                    </td>
            
                    <td class="text-left">
                        {{ $album->tipo_album == 'normal' ? $album->name : $album->titulo }}
                    </td>

                    <td>
                        <span class="badge {{ $album->tipo_album == 'normal' ? 'badge-primary' : 'badge-info' }}">
                            {{ $album->tipo_album == 'normal' ? 'Normal' : 'Compartilhado' }}
                        </span>
                    </td>

                    <td>
                        @if($album->tipo_album == 'compartilhado')
                            @if($album->aceita_uploads)
                                <span class="badge badge-success">Ativado</span>
                            @else
                                <span class="badge badge-danger">Desativado</span>
                            @endif
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    <td>
                        @if($album->tipo_album == 'normal')
                            <a href="{{ route('album.show',$album->slug) }}" class="btn btn-sm btn-success">Ver</a>
                            @permission('create-albums')
                            <a href="{{ route('album.edit',$album->slug) }}" class="btn btn-sm btn-info">Editar</a>
                            <form action="{{ route('album.destroy',$album->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir este álbum?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                            @endpermission
                        @else
                            <a href="{{ route('meu-album-compartilhado.show',$album->slug) }}" class="btn btn-sm btn-success">Ver</a>
                            @permission('edit-shared-albums')
                            <a href="{{ route('meu-album-compartilhado.edit',$album->slug) }}" class="btn btn-sm btn-info">Editar</a>
                            @endpermission
                            
                            @permission('toggle-uploads-shared-albums')
                            <form action="{{ route('meu-album-compartilhado.update', $album->slug) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="titulo" value="{{ $album->titulo }}">
                                <input type="hidden" name="aceita_uploads" value="{{ $album->aceita_uploads ? '0' : '1' }}">
                                <button type="submit" class="btn btn-sm {{ $album->aceita_uploads ? 'btn-outline-secondary' : 'btn-outline-danger' }}" title="{{ $album->aceita_uploads ? 'Desativar uploads' : 'Ativar uploads' }}">
                                    <i class="fas {{ $album->aceita_uploads ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                </button>
                            </form>
                            @endpermission

                            @permission('delete-shared-albums')
                            <form action="{{ route('meu-album-compartilhado.destroy',$album->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                            @endpermission
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    
    <p class="bg-info p-3 text-white rounded">Nenhum álbum encontrado ainda</p>

    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.album-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    
    function updateUI() {
        const checked = document.querySelectorAll('.album-checkbox:checked');
        bulkActions.style.display = checked.length > 0 ? 'block' : 'none';
        if(selectAll) selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateUI();
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateUI));

    // Ação: Excluir
    document.getElementById('btn-bulk-delete').addEventListener('click', function() {
        if(!confirm('Tem certeza que deseja excluir os álbuns selecionados?')) return;
        const container = document.getElementById('delete-ids-container');
        container.innerHTML = '';
        document.querySelectorAll('.album-checkbox:checked').forEach(cb => {
            container.innerHTML += `<input type="hidden" name="album_ids[]" value="${cb.value}">`;
        });
        document.getElementById('form-bulk-delete').submit();
    });

    // Ação: Ativar Uploads
    document.getElementById('btn-bulk-activate').addEventListener('click', function() {
        const container = document.getElementById('toggle-ids-container');
        container.innerHTML = '';
        document.querySelectorAll('.album-checkbox:checked').forEach(cb => {
            container.innerHTML += `<input type="hidden" name="album_ids[]" value="${cb.value}">`;
        });
        document.getElementById('toggle-status-value').value = '1';
        document.getElementById('form-bulk-toggle').submit();
    });

    // Ação: Desativar Uploads
    document.getElementById('btn-bulk-deactivate').addEventListener('click', function() {
        const container = document.getElementById('toggle-ids-container');
        container.innerHTML = '';
        document.querySelectorAll('.album-checkbox:checked').forEach(cb => {
            container.innerHTML += `<input type="hidden" name="album_ids[]" value="${cb.value}">`;
        });
        document.getElementById('toggle-status-value').value = '0';
        document.getElementById('form-bulk-toggle').submit();
    });
});
</script>

@endsection
