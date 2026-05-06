@extends('backend.layouts.app')

@section('content')

@breadcrumb()
    <li class="breadcrumb-item active">Equipe</li>
@endbreadcrumb

<div class="bg-white p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Membros da Equipe</h3>
        <a href="{{ route('team.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Adicionar Membro
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{ photon_notification($errors)}}

    @if (count($teams) > 0 )
    <form id="bulk-delete-form" action="{{ route('team.bulk_destroy') }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="mb-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
            <div>
                <div class="custom-control custom-checkbox d-inline-block mr-3">
                    <input type="checkbox" class="custom-control-input" id="select-all">
                    <label class="custom-control-label" for="select-all"><strong>Selecionar Todos</strong></label>
                </div>
                <span class="text-muted">{{ $teams->total() }} membros encontrados</span>
            </div>
            <button type="submit" id="btn-bulk-delete" class="btn btn-danger btn-sm" style="display: none;" onclick="return confirm('Tem certeza que deseja excluir os membros selecionados?');">
                <i class="fas fa-trash"></i> EXCLUIR SELECIONADOS (<span id="count-selected">0</span>)
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th width="40"><i class="fas fa-check-square"></i></th>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teams as $team)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input team-checkbox" id="team_{{ $team->id }}" name="team_ids[]" value="{{ $team->id }}">
                                <label class="custom-control-label" for="team_{{ $team->id }}"></label>
                            </div>
                        </td>
                        <td>{{ $team->id }}</td>
                        <td>
                            <img src="{{ photon_thumbnail($team->thumbnail) }}" width="50" height="50" style="border-radius: 50%; object-fit: cover;">
                        </td>
                        <td>{{ $team->name }}</td>
                        <td class="text-left small">{{ str_limit($team->description, 100) }}</td>
                        <td>
                            <a href="{{ route('team.show', $team->slug) }}" class="btn btn-sm btn-success">Ver</a>
                            <a href="{{ route('team.edit', $team->slug) }}" class="btn btn-sm btn-info">Editar</a>
                            <form action="{{ route('team.destroy', $team->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir este membro?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-3">
        {{ $teams->links() }}
    </div>

    @else
        <p class="bg-info p-3 text-white rounded">Nenhum membro da equipe encontrado.</p>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.team-checkbox');
    const bulkDeleteBtn = document.getElementById('btn-bulk-delete');
    const countSelected = document.getElementById('count-selected');

    function updateUI() {
        const checked = document.querySelectorAll('.team-checkbox:checked');
        const count = checked.length;
        if(countSelected) countSelected.textContent = count;
        if(bulkDeleteBtn) bulkDeleteBtn.style.display = count > 0 ? 'inline-block' : 'none';
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
});
</script>
@endsection
