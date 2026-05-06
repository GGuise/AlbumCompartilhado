@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item">
        <a href="{{ route('meu-album-compartilhado.index') }}">Álbuns Compartilhados</a>
    </li>
    <li class="breadcrumb-item active">Todos</li>
@endbreadcrumb

<div class="row">
    <div class="col-12">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white p-3">
            <h3 class="mb-4">Álbuns Compartilhados 
                <a href="{{ route('meu-album-compartilhado.create') }}" class="btn btn-sm btn-primary float-right">Adicionar Novo</a>
            </h3>

            @if(count($albums) > 0)
                <div class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                    <div>
                        <div class="custom-control custom-checkbox d-inline-block mr-3">
                            <input type="checkbox" class="custom-control-input" id="select-all">
                            <label class="custom-control-label" for="select-all"><strong>Selecionar Todos</strong></label>
                        </div>
                        <span class="text-muted">{{ $albums->total() }} álbuns encontrados</span>
                    </div>
                    <div id="bulk-actions" style="display: none;">
                        <button type="button" class="btn btn-sm btn-danger mr-1" id="btn-bulk-delete">
                            <i class="fas fa-trash"></i> Excluir Selecionados
                        </button>
                        <button type="button" class="btn btn-sm btn-success mr-1" id="btn-bulk-activate">
                            <i class="fas fa-lock-open"></i> Ativar Selecionados
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" id="btn-bulk-deactivate">
                            <i class="fas fa-lock"></i> Desativar Selecionados
                        </button>
                    </div>
                </div>

                {{-- Formulários Ocultos para Ações em Massa --}}
                <form id="form-bulk-delete" action="{{ route('meu-album-compartilhado.bulk_destroy') }}" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                    <div id="delete-ids-container"></div>
                </form>
                
                <form id="form-bulk-toggle" action="{{ route('meu-album-compartilhado.bulk_toggle_uploads') }}" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="status" id="toggle-status-value">
                    <div id="toggle-ids-container"></div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th width="40"><i class="fas fa-check-square"></i></th>
                                <th>ID</th>
                                <th>Capa</th>
                                <th>Título</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($albums as $album)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input album-checkbox" id="album_{{ $album->id }}" name="ids[]" value="{{ $album->id }}">
                                        <label class="custom-control-label" for="album_{{ $album->id }}"></label>
                                    </div>
                                </td>
                                <td>{{ $album->id }}</td>
                                <td>
                                    @if($album->foto_topo)
                                        <img src="{{ asset('storage/images/'.$album->foto_topo) }}" width="60" height="40" style="object-fit: cover;" alt="{{ $album->titulo }}">
                                    @else
                                        <span class="text-muted small">Sem Capa</span>
                                    @endif
                                </td>
                                <td>{{ $album->titulo }}</td>
                                <td>
                                    @if($album->aceita_uploads)
                                        <span class="badge badge-success">Ativado</span>
                                    @else
                                        <span class="badge badge-danger">Desativado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('meu-album-compartilhado.show', $album->slug) }}" class="btn btn-sm btn-success">Ver</a>
                                    
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

                                    <a href="{{ route('meu-album-compartilhado.edit', $album->slug) }}" class="btn btn-sm btn-info">Editar</a>
                                    
                                    <form action="{{ route('meu-album-compartilhado.destroy', $album->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center p-4">Nenhum álbum compartilhado encontrado.</p>
            @endif

            <div class="mt-3">
                {{ $albums->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.album-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const countSelected = document.getElementById('count-selected');

    function updateUI() {
        const checked = document.querySelectorAll('.album-checkbox:checked');
        const count = checked.length;
        bulkActions.style.display = count > 0 ? 'block' : 'none';
        if(selectAll) selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateUI();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateUI);
    });

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

    // Ação: Ativar
    document.getElementById('btn-bulk-activate').addEventListener('click', function() {
        const container = document.getElementById('toggle-ids-container');
        container.innerHTML = '';
        document.querySelectorAll('.album-checkbox:checked').forEach(cb => {
            container.innerHTML += `<input type="hidden" name="album_ids[]" value="${cb.value}">`;
        });
        document.getElementById('toggle-status-value').value = '1';
        document.getElementById('form-bulk-toggle').submit();
    });

    // Ação: Desativar
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
