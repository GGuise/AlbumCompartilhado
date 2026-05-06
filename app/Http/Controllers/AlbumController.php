<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:read-albums'])->only(['index', 'show']);
        $this->middleware(['permission:create-albums'])->except(['index', 'show']);
    }
    public function index()
    {   
        $user = auth()->user();
        $canViewAll = $user->hasPermission('view-all-content') || $user->hasRole('superadministrator');
        $isReadOnly = $user->hasPermission('read-albums') && !$user->hasPermission('create-albums');

        if ($canViewAll || $isReadOnly) {
            $albums = Album::latest()->get();
            $sharedAlbums = \App\MeuAlbumCompartilhado::latest()->get();
        } else {
            $albums = Album::where('user_id', $user->id)->latest()->get();
            $sharedAlbums = \App\MeuAlbumCompartilhado::where('user_id', $user->id)->latest()->get();
        }

        // Marcar cada um para saber o tipo na View
        foreach($albums as $a) $a->tipo_album = 'normal';
        foreach($sharedAlbums as $s) $s->tipo_album = 'compartilhado';

        $data["albums"] = $albums->concat($sharedAlbums)->sortByDesc('created_at');
        
        return view('backend.album.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.album.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string',
            'banner' => 'image'
        ]);

        $imgName = ($request->hasFile('banner') || $request->cropped_banner) ? \photon_image_process($request,"banner") : 'default.jpg';


        Album::create([
            'name' => $request->name,
            'slug' => str_slug($request->name),
            'banner' => $imgName,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('album.index')->with('status','Album successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        $data["album"] = $album;
        return view('backend.album.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {   
        $data["album"] = $album;
        return view('backend.album.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $validation = $request->validate([
            'name' => 'required|string',
            'banner' => 'image'
        ]);

        
        if($request->banner || $request->cropped_banner){
            $imgName = \photon_image_process($request, "banner");
        }else{
            $imgName = $album->banner;
        }


        $album->update([
            'name' => $request->name,
            'slug' => str_slug($request->name),
            'banner' => $imgName
        ]);

        return redirect()->route('album.index')->with('status','Album updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        $album->delete();
        return redirect()->route('album.index')->with('status','Album deleted successfully');
    }



    /**
     * Show frontend Album
     *
     * @param  \App\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function catshow($slug)
    {

        $data['album'] = Album::where('slug',$slug)->first();
    
        return view('single',$data);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
        ]);

        $deletedCount = 0;
        foreach ($request->album_ids as $item) {
            $parts = explode('_', $item);
            $tipo = $parts[0];
            $id = $parts[1];

            if ($tipo == 'normal') {
                $a = Album::find($id);
                if($a) { $a->delete(); $deletedCount++; }
            } else {
                $s = \App\MeuAlbumCompartilhado::find($id);
                if($s) { $s->delete(); $deletedCount++; }
            }
        }

        return redirect()->back()->with('status', "{$deletedCount} álbum(ns) excluído(s) com sucesso!");
    }

    public function bulkToggleUploads(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
            'status' => 'required|in:0,1'
        ]);

        $status = $request->status;
        $updatedCount = 0;

        foreach ($request->album_ids as $item) {
            $parts = explode('_', $item);
            if ($parts[0] == 'compartilhado') {
                $id = $parts[1];
                $s = \App\MeuAlbumCompartilhado::find($id);
                if($s) {
                    $s->update(['aceita_uploads' => $status]);
                    $updatedCount++;
                }
            }
        }

        $statusText = $status ? 'ativados' : 'desativados';
        return redirect()->back()->with('status', "Uploads de {$updatedCount} álbuns compartilhados foram {$statusText}!");
    }
}
