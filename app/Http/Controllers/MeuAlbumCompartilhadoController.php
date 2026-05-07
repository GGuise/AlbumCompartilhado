<?php

namespace App\Http\Controllers;

use App\MeuAlbumCompartilhado;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class MeuAlbumCompartilhadoController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:read-meualbumcompartilhado'])->only(['index', 'show']);
        $this->middleware(['permission:create-meualbumcompartilhado'])->only(['create', 'store', 'uploadFotos', 'uploadFotosAjax']);
        $this->middleware(['permission:update-meualbumcompartilhado'])->only(['edit', 'update']);
        $this->middleware(['permission:delete-meualbumcompartilhado'])->only(['destroy', 'destroyFoto']);
    }

    public function index()
    {
        $user = auth()->user();
        $canViewAll = $user->hasPermission('view-all-content') || $user->hasRole('superadministrator');
        
        if ($canViewAll) {
            $data["albums"] = MeuAlbumCompartilhado::latest()->paginate(10);
        } else {
            $data["albums"] = MeuAlbumCompartilhado::where('user_id', $user->id)->latest()->paginate(10);
        }
        return view('backend.meu_album_compartilhado.index', $data);
    }

    public function create()
    {
        return view('backend.meu_album_compartilhado.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'foto_topo' => 'nullable',
            'foto_topo_web' => 'nullable',
            'foto_topo_mobile' => 'nullable',
            'imagem_fundo' => 'nullable',
            'texto_personalizado' => 'nullable|string',
            'pequena_mensagem' => 'nullable|string',
            'tipo_fundo' => 'nullable|in:cor,imagem',
            'cor_fundo' => 'nullable|string|max:20',
        ]);

        $imgName = ($request->hasFile('foto_topo') || $request->cropped_foto_topo) ? \photon_image_process($request, "foto_topo") : null;
        $fotoWeb = ($request->hasFile('foto_topo_web') || $request->cropped_foto_topo_web) ? \photon_image_process($request, "foto_topo_web") : null;
        $fotoMobile = ($request->hasFile('foto_topo_mobile') || $request->cropped_foto_topo_mobile) ? \photon_image_process($request, "foto_topo_mobile") : null;
        $imagemFundo = ($request->hasFile('imagem_fundo') || $request->cropped_imagem_fundo) ? \photon_image_process($request, "imagem_fundo") : null;

        // Criar pasta no Google Drive automaticamente
        $driveService = new GoogleDriveService();
        $driveFolderId = null;

        if ($driveService->isConfigured()) {
            $user = auth()->user();
            $folderName = $request->titulo . ' - ' . $user->name . ' (ID ' . $user->id . ')';
            $driveFolderId = $driveService->createFolder($folderName);
        }

        MeuAlbumCompartilhado::create([
            'titulo' => $request->titulo,
            'slug' => str_slug($request->titulo) . '-' . time(),
            'foto_topo' => $imgName,
            'foto_topo_web' => $fotoWeb,
            'foto_topo_mobile' => $fotoMobile,
            'tipo_fundo' => $request->tipo_fundo ?? 'cor',
            'cor_fundo' => $request->cor_fundo ?? '#ffffff',
            'imagem_fundo' => $imagemFundo,
            'texto_personalizado' => $request->texto_personalizado,
            'pequena_mensagem' => $request->pequena_mensagem,
            'aceita_uploads' => $request->aceita_uploads ? true : false,
            'google_drive_folder_id' => $driveFolderId,
            'user_id' => auth()->id()
        ]);

        $msg = 'Álbum compartilhado criado com sucesso!';
        if ($driveFolderId) {
            $msg .= ' Pasta criada no Google Drive.';
        } elseif ($driveService->isConfigured()) {
            $msg .= ' (Aviso: Não foi possível criar a pasta no Drive)';
        }

        return redirect()->route('meu-album-compartilhado.index')->with('status', $msg);
    }

    public function show(MeuAlbumCompartilhado $meu_album_compartilhado)
    {
        $data["album"] = $meu_album_compartilhado;
        return view('backend.meu_album_compartilhado.show', $data);
    }

    public function edit(MeuAlbumCompartilhado $meu_album_compartilhado)
    {
        $data["album"] = $meu_album_compartilhado;
        return view('backend.meu_album_compartilhado.edit', $data);
    }

    public function update(Request $request, MeuAlbumCompartilhado $meu_album_compartilhado)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'foto_topo' => 'nullable',
            'foto_topo_web' => 'nullable',
            'foto_topo_mobile' => 'nullable',
            'imagem_fundo' => 'nullable',
            'texto_personalizado' => 'nullable|string',
            'pequena_mensagem' => 'nullable|string',
            'tipo_fundo' => 'nullable|in:cor,imagem',
            'cor_fundo' => 'nullable|string|max:20',
        ]);

        $imgName = ($request->hasFile('foto_topo') || $request->cropped_foto_topo) ? \photon_image_process($request, "foto_topo") : $meu_album_compartilhado->foto_topo;
        $fotoWeb = ($request->hasFile('foto_topo_web') || $request->cropped_foto_topo_web) ? \photon_image_process($request, "foto_topo_web") : $meu_album_compartilhado->foto_topo_web;
        $fotoMobile = ($request->hasFile('foto_topo_mobile') || $request->cropped_foto_topo_mobile) ? \photon_image_process($request, "foto_topo_mobile") : $meu_album_compartilhado->foto_topo_mobile;
        $imagemFundo = ($request->hasFile('imagem_fundo') || $request->cropped_imagem_fundo) ? \photon_image_process($request, "imagem_fundo") : $meu_album_compartilhado->imagem_fundo;

        $meu_album_compartilhado->update([
            'titulo' => $request->titulo,
            'slug' => str_slug($request->titulo), // Removido o time() no update para não quebrar a URL atual
            'foto_topo' => $imgName,
            'foto_topo_web' => $fotoWeb,
            'foto_topo_mobile' => $fotoMobile,
            'tipo_fundo' => $request->tipo_fundo ?? 'cor',
            'cor_fundo' => $request->cor_fundo ?? '#ffffff',
            'imagem_fundo' => $imagemFundo,
            'texto_personalizado' => $request->texto_personalizado,
            'pequena_mensagem' => $request->pequena_mensagem,
            'aceita_uploads' => $request->aceita_uploads ? true : false,
        ]);

        return redirect()->route('meu-album-compartilhado.edit', $meu_album_compartilhado->slug)->with('status', 'Álbum compartilhado atualizado com sucesso!');
    }

    public function destroy(MeuAlbumCompartilhado $meu_album_compartilhado)
    {
        $meu_album_compartilhado->delete();
        return redirect()->route('meu-album-compartilhado.index')->with('status', 'Álbum compartilhado removido com sucesso!');
    }

    public function uploadFotos(Request $request, MeuAlbumCompartilhado $album)
    {
        $request->validate([
            'fotos' => 'required',
            'fotos.*' => 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv,webm|max:51200'
        ]);

        $driveService = new GoogleDriveService();

        if($request->hasFile('fotos')) {
            foreach($request->file('fotos') as $file) {
                $imgName = sprintf('%s%s.%s', str_random(10), md5(time()), $file->extension());
                $file->storeAs('images', $imgName);
                
                \DB::table('meu_album_compartilhado_fotos')->insert([
                    'meu_album_compartilhado_id' => $album->id,
                    'foto_path' => $imgName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Enviar para o Google Drive
                if ($driveService->isConfigured() && $album->google_drive_folder_id) {
                    $localPath = storage_path('app/public/images/' . $imgName);
                    if (file_exists($localPath)) {
                        $driveService->uploadFile($localPath, $imgName, $album->google_drive_folder_id);
                    }
                }
            }
        }

        return redirect()->back()->with('status', 'Fotos enviadas com sucesso!');
    }

    public function uploadFotosAjax(Request $request, MeuAlbumCompartilhado $album)
    {
        // Se houver erro de validação, o Laravel redireciona com 422. 
        $request->validate([
            'foto' => 'required|file|max:102400' 
        ]);

        if($request->hasFile('foto')) {
            $file = $request->file('foto');
            $originalName = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());
            
            $imgName = str_random(10) . '_' . time() . '.' . $ext;
            $file->move(public_path('storage/images'), $imgName);
            
            \DB::table('meu_album_compartilhado_fotos')->insert([
                'meu_album_compartilhado_id' => $album->id,
                'foto_path' => $imgName,
                'remetente_nome' => $request->nome_usuario,
                'remetente_mensagem' => $request->mensagem,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Enviar para o Google Drive
            $driveService = new GoogleDriveService();
            $driveFileId = null;
            if ($driveService->isConfigured() && $album->google_drive_folder_id) {
                $localPath = public_path('storage/images/' . $imgName);
                if (file_exists($localPath)) {
                    $driveFileId = $driveService->uploadFile($localPath, $imgName, $album->google_drive_folder_id);
                }
            }

            return response()->json([
                'success' => true,
                'drive_uploaded' => $driveFileId !== null
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function destroyFoto(\App\MeuAlbumCompartilhadoFoto $foto)
    {
        $foto->delete();
        return redirect()->back()->with('status', 'Foto removida com sucesso!');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
            'album_ids.*' => 'integer|exists:meu_album_compartilhados,id'
        ]);

        MeuAlbumCompartilhado::whereIn('id', $request->album_ids)->delete();

        $count = count($request->album_ids);
        return redirect()->route('meu-album-compartilhado.index')->with('status', "{$count} álbum(ns) excluído(s) com sucesso!");
    }

    public function bulkToggleUploads(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
            'album_ids.*' => 'integer|exists:meu_album_compartilhados,id',
            'status' => 'required|in:0,1'
        ]);

        MeuAlbumCompartilhado::whereIn('id', $request->album_ids)->update([
            'aceita_uploads' => $request->status
        ]);

        $statusText = $request->status ? 'ativados' : 'desativados';
        $count = count($request->album_ids);
        return redirect()->back()->with('status', "Uploads de {$count} álbuns foram {$statusText}!");
    }
}
