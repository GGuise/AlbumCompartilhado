<!-- Modal de Recorte -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cropperModalLabel">Ajustar Imagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container" style="max-height: 500px;">
          <img id="cropperImage" src="" style="max-width: 100%;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnCrop">Cortar e Salvar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper;
    let currentInput;
    let currentPreview;
    let currentHidden;
    const modal = $('#cropperModal');
    const image = document.getElementById('cropperImage');

    // Função para inicializar o cropper em qualquer input de arquivo
    window.initCropper = function(selector, options = {}) {
        $(document).on('change', selector, function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                currentInput = e.target;
                const file = files[0];
                const reader = new FileReader();
                
                // Procurar por preview e hidden input associados
                const parent = $(currentInput).closest('.form-group');
                currentPreview = parent.find('.cropper-preview');
                currentHidden = parent.find('.cropper-hidden');
                
                // Se não existir o hidden, cria um
                if (currentHidden.length === 0) {
                    const name = $(currentInput).attr('name');
                    const hiddenInput = $('<input type="hidden" name="cropped_' + name + '" class="cropper-hidden">');
                    $(currentInput).after(hiddenInput);
                    currentHidden = hiddenInput;
                }

                reader.onload = function(event) {
                    image.src = event.target.result;
                    modal.modal('show');
                };
                reader.readAsDataURL(file);
            }
        });
    };

    modal.on('shown.bs.modal', function() {
        // Determinar aspect ratio baseado no input (opcional)
        let aspectRatio = NaN; // Livre por padrão
        const name = $(currentInput).attr('name');
        
        if (name.includes('web')) aspectRatio = 1920 / 600;
        if (name.includes('mobile')) aspectRatio = 1080 / 1920;
        if (name === 'banner' || name === 'foto_topo') aspectRatio = 16 / 9;

        cropper = new Cropper(image, {
            aspectRatio: aspectRatio,
            viewMode: 1,
            autoCropArea: 1,
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });

    document.getElementById('btnCrop').addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({
            width: 1920, // Max width
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function(blob) {
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                const base64data = reader.result;
                
                // Salvar no input hidden
                currentHidden.val(base64data);
                
                // Atualizar preview se existir
                if (currentPreview.length) {
                    currentPreview.attr('src', base64data).show();
                } else {
                    // Se não tiver preview, cria um pequeno
                    parent = $(currentInput).closest('.form-group');
                    if (parent.find('img').length === 0) {
                        $(currentInput).before('<img src="' + base64data + '" class="img-thumbnail mb-2 cropper-preview" style="max-height: 200px;">');
                    } else {
                        parent.find('img').attr('src', base64data);
                    }
                }
                
                modal.modal('hide');
            };
        }, 'image/jpeg');
    });
});
</script>
