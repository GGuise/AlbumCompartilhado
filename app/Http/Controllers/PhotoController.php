<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Album;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware(['permission:read-photos'])->only(['index', 'show']);
        $this->middleware(['permission:create-albums'])->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $canViewAll = $user->hasPermission('view-all-content') || $user->hasRole('superadministrator');
        $isReadOnly = $user->hasPermission('read-photos') && !$user->hasPermission('create-albums');

        if ($canViewAll || $isReadOnly) {
            // Pegar fotos normais
            $photos = Photo::latest()->get();
            foreach($photos as $p) $p->tipo_foto = 'normal';

            // Pegar fotos de álbuns compartilhados
            $sharedPhotos = \DB::table('meu_album_compartilhado_fotos')
                ->join('meu_album_compartilhados', 'meu_album_compartilhados.id', '=', 'meu_album_compartilhado_fotos.meu_album_compartilhado_id')
                ->select('meu_album_compartilhado_fotos.*', 'meu_album_compartilhados.titulo as album_nome')
                ->get();
            
            foreach($sharedPhotos as $sp) {
                $sp->tipo_foto = 'compartilhada';
                $sp->image = $sp->foto_path; // Padronizar campo de imagem
                $sp->title = "Enviada por: " . ($sp->remetente_nome ?: 'Anônimo');
                $sp->slug = $sp->id; // Usar ID como slug para fotos compartilhadas
            }

            // Unificar
            $allPhotos = $photos->concat($sharedPhotos)->sortByDesc('created_at');
            
            // Paginação manual simples (já que unificamos collections)
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 50;
            $currentItems = $allPhotos->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $paginatedPhotos = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $allPhotos->count(), $perPage);
            $paginatedPhotos->setPath(request()->url());

            $data["photos"] = $paginatedPhotos;
        } else {
            $data["photos"] = $user->photos()->paginate(50);
        }
        return view('backend.photo.index',$data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $data["albums"] = Album::all();
        return view('backend.photo.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Se houver erro de validação, o Laravel redireciona com 422. 
        // Vou deixar a validação o mais simples possível.
        $request->validate([
            'title' => 'required|max:100',
        ]);

        $imgName = 'default.jpg';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . str_random(5) . '.' . $ext;
            
            // Movemos o arquivo diretamente sem processamento para testar
            $file->move(public_path('storage/images'), $fileName);
            $imgName = $fileName;
        }

        Photo::create([
            'title' => $request->title,
            'slug' => str_slug($request->title) . '-' . time(),
            'description' => $request->description ?? '',
            'image' => $imgName,
            'user_id' => auth()->user()->id,
            'album_id' => $request->album_id ?? 1 // Fallback para album 1 se não vier
        ]);

        return redirect()->route('photo.index')->with('status', 'Arquivo enviado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Photo  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {

        $data["photo"] = $photo;
        return view('backend.photo.show',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Photo  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        $data["albums"] = Album::all();
        $data["photo"] = $photo;
        return view('backend.photo.edit',$data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|max:102400'
        ]);

        $imgName = $photo->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension());
            $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'webm', 'm4v'];
            
            if (in_array($ext, $videoExtensions)) {
                $fileName = time() . '_' . str_random(5) . '.' . $ext;
                $file->move(public_path('storage/images'), $fileName);
                $imgName = $fileName;
            } else {
                try {
                    $imgName = \photon_image_process($request, 'image');
                } catch (\Exception $e) {
                    $fileName = time() . '_' . str_random(5) . '.' . $ext;
                    $file->move(public_path('storage/images'), $fileName);
                    $imgName = $fileName;
                }
            }
        }

        $photo->update([
            'title' => $request->title,
            'slug' => str_slug($request->title) . '-' . time(),
            'description' => $request->description,
            'image' => $imgName,
            'album_id' => $request->album_id
        ]);

        return redirect()->route('photo.index')->with('status', 'Arquivo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        // Tentar encontrar na tabela normal primeiro
        $photo = Photo::where('slug', $slug)->first();
        if ($photo) {
            $photo->delete();
            return redirect()->route('photo.index')->with('status','Foto excluída com sucesso!');
        }

        // Se não encontrar, tentar na tabela de compartilhadas (o slug aqui é o ID)
        $sharedPhoto = \App\MeuAlbumCompartilhadoFoto::find($slug);
        if ($sharedPhoto) {
            $sharedPhoto->delete();
            return redirect()->route('photo.index')->with('status','Foto de álbum compartilhado excluída com sucesso!');
        }

        return redirect()->route('photo.index')->with('error','Foto não encontrada.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'photo_ids' => 'required|array',
        ]);

        $deletedCount = 0;
        foreach ($request->photo_ids as $item) {
            // O ID vem no formato "tipo_id", ex: "normal_10" ou "compartilhada_5"
            $parts = explode('_', $item);
            $tipo = $parts[0];
            $id = $parts[1];

            if ($tipo == 'normal') {
                $p = Photo::find($id);
                if($p) { $p->delete(); $deletedCount++; }
            } else {
                $sp = \App\MeuAlbumCompartilhadoFoto::find($id);
                if($sp) { $sp->delete(); $deletedCount++; }
            }
        }

        return redirect()->route('photo.index')->with('status', "{$deletedCount} foto(s) excluída(s) com sucesso!");
    }
}
