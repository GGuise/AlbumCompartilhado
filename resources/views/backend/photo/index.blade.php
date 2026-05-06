@extends('backend.layouts.app')

@section('content')
@breadcrumb()
    <li class="breadcrumb-item active">Fotos</li>
@endbreadcrumb

<div class="bg-white p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Todas as Fotos (Geral)</h3>
            @permission('create-albums')
            <a href="{{ route('photo.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Adicionar Foto
            </a>
            @endpermission
        </div>
        {{ photon_notification($errors)}}

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
    @if (count($photos) > 0 )

    @permission('create-albums')
    <form id="bulk-delete-form" action="{{ route('photo.bulk_destroy') }}" method="POST">
        @csrf
        @method('DELETE')
    @endpermission

    <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded">
        <div>
            @permission('create-albums')
            <div class="custom-control custom-checkbox d-inline-block mr-3">
                <input type="checkbox" class="custom-control-input" id="select-all">
                <label class="custom-control-label" for="select-all"><strong>Selecionar Todas</strong></label>
            </div>
            @endpermission
            <span class="text-muted">{{ $photos->total() }} fotos encontradas (Normal + Compartilhadas)</span>
        </div>
        @permission('create-albums')
        <button type="submit" id="btn-bulk-delete" class="btn btn-danger btn-sm" style="display: none;" onclick="return confirm('Tem certeza que deseja excluir as fotos selecionadas?');">
            <i class="fas fa-trash"></i> EXCLUIR SELECIONADAS (<span id="count-selected">0</span>)
        </button>
        @endpermission
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center mt-2">
            <thead>
                <tr>
                    @permission('create-albums')
                    <th width="40"><i class="fas fa-check-square"></i></th>
                    @endpermission
                    <th>Tipo</th>
                    <th>Imagem</th>
                    <th>Título / Álbum</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($photos as $photo)
                <tr>
                    @permission('create-albums')
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input photo-checkbox" id="photo_{{ $photo->tipo_foto }}_{{ $photo->id }}" name="photo_ids[]" value="{{ $photo->tipo_foto }}_{{ $photo->id }}">
                            <label class="custom-control-label" for="photo_{{ $photo->tipo_foto }}_{{ $photo->id }}"></label>
                        </div>
                    </td>
                    @endpermission
                    <td>
                        @if($photo->tipo_foto == 'normal')
                            <span class="badge badge-primary">Normal</span>
                        @else
                            <span class="badge badge-info">Compartilhada</span>
                        @endif
                    </td>
                    <td>
                        @if($photo->tipo_foto == 'normal')
                            <img src="{{ photon_thumbnail($photo->image) }}" width="50" height="50" style="object-fit: cover;">
                        @else
                            @php $ext = pathinfo($photo->image, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'wmv', 'webm']))
                                <div style="width: 50px; height: 50px; background: #000; color: #fff; font-size: 10px; display: flex; align-items: center; justify-content: center;">VÍDEO</div>
                            @else
                                <img src="{{ asset('storage/images/'.$photo->image) }}" width="50" height="50" style="object-fit: cover;">
                            @endif
                        @endif
                    </td>
            
                    <td class="text-left">
                        <strong>{{ $photo->title }}</strong><br>
                        <small class="text-muted">
                            @if($photo->tipo_foto == 'normal')
                                Álbum: {{ $photo->album ? $photo->album->name : 'Nenhum' }}
                            @else
                                Álbum: {{ $photo->album_nome }}
                            @endif
                        </small>
                    </td>

                    <td>
                        @if($photo->tipo_foto == 'normal')
                            <a href="{{ route('photo.show',$photo->slug) }}" class="btn btn-sm btn-success">Detalhes</a>
                            @permission('create-albums')
                            <form action="{{ route('photo.destroy',$photo->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta foto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Excluir</button>
                            </form>
                            @endpermission
                        @else
                            <a href="{{ route('meu-album-compartilhado.show', \App\MeuAlbumCompartilhado::where('titulo', $photo->album_nome)->first()->slug ?? '#') }}" class="btn btn-sm btn-info">Ver Álbum</a>
                            @permission('create-albums')
                            <form action="{{ route('photo.destroy', $photo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta foto compartilhada?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Excluir</button>
                            </form>
                            @endpermission
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @permission('create-albums')
    </form>
    @endpermission

    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">Mostrando {{ $photos->firstItem() }} a {{ $photos->lastItem() }} de {{ $photos->total() }} fotos (Unificadas)</small>
        {{ $photos->links()}}
    </div>
    @else
    
    <p class="bg-info p-3 text-white rounded">Nenhuma foto encontrada ainda em nenhuma categoria.</p>

    @endif

</div>

@permission('create-albums')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.photo-checkbox');
    const bulkDeleteBtn = document.getElementById('btn-bulk-delete');
    const countSelected = document.getElementById('count-selected');

    function updateUI() {
        const checked = document.querySelectorAll('.photo-checkbox:checked');
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
@endpermission
@endsection
