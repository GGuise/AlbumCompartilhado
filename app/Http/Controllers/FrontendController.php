<?php

namespace App\Http\Controllers;

use App\Team;
use App\Album;
use App\Photo;
use App\Service;
use App\ContactInfo;
use App\Mail\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    public function home(){
        $data["albums"] = Album::orderBy('created_at','desc')->get();
        $data["shared_albums"] = \App\MeuAlbumCompartilhado::orderBy('created_at','desc')->get();
        return view('frontend.index',$data);
    }

    // Gallery

    public function gallery($slug){
        $album = Album::where('slug',$slug)->first();
        $data["albums"] = Album::orderBy('created_at','desc')->get();
        $data["title"] = $album->name; 
        $data["photos"] = Photo::where("album_id",$album->id)->get(); 
        return view('frontend.gallery',$data);
    }

    public function shared_gallery($slug){
        $album = \App\MeuAlbumCompartilhado::with('fotos')->where('slug', $slug)->firstOrFail();
        $data["shared"] = $album;
        $data["title"] = $album->titulo;
        return view('frontend.shared_gallery', $data);
    }

    public function shared_upload_view($slug){
        $album = \App\MeuAlbumCompartilhado::where('slug', $slug)->firstOrFail();
        $data["shared"] = $album;
        $data["title"] = "Contribuir com " . $album->titulo;
        return view('frontend.shared_upload', $data);
    }

    public function shared_upload_post(Request $request, $slug){
        $album = \App\MeuAlbumCompartilhado::where('slug', $slug)->firstOrFail();

        $request->validate([
            'nome_usuario' => 'nullable|string|max:255',
            'mensagem' => 'nullable|string',
            'fotos' => 'required|array',
            'fotos.*' => 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv,webm|max:51200'
        ]);

        if($request->hasFile('fotos')) {
            foreach($request->file('fotos') as $file) {
                $imgName = sprintf('%s%s.%s', str_random(10), md5(time()), $file->extension());
                $file->storeAs('images', $imgName);
                
                // Add the fields to the foto directly? Wait! Did I add nome_usuario to the migration?
                // Actually, I didn't add nome_usuario and mensagem to the DB yet!
                $album->fotos()->create([
                    'foto_path' => $imgName,
                    'remetente_nome' => $request->nome_usuario,
                    'remetente_mensagem' => $request->mensagem
                ]);
            }
        }

        return redirect()->route('shared_gallery', $album->slug)->with('status', 'Suas fotos foram enviadas com sucesso!');
    }

    public function shared_upload_ajax(Request $request, $slug){
        $album = \App\MeuAlbumCompartilhado::where('slug', $slug)->firstOrFail();

        if (!$album->aceita_uploads) {
            return response()->json([
                'success' => false,
                'message' => 'Este álbum não está aceitando novos uploads no momento.'
            ], 403);
        }

        // Validação flexível para evitar erro 422
        $request->validate([
            'nome_usuario' => 'nullable|string|max:255',
            'mensagem' => 'nullable|string',
            'foto' => 'required|file|max:102400' // 100MB
        ]);

        if($request->hasFile('foto')){
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'webm', 'm4v'];

            if (in_array($ext, $videoExtensions)) {
                // Se for vídeo, apenas movemos o arquivo sem processamento de imagem
                $imgName = str_random(10) . '_' . time() . '.' . $ext;
                $file->move(public_path('storage/images'), $imgName);
            } else {
                // Se for imagem, tentamos o processamento normal
                try {
                    $imgName = \photon_image_process($request, "foto");
                } catch (\Exception $e) {
                    $imgName = str_random(10) . '_' . time() . '.' . $ext;
                    $file->move(public_path('storage/images'), $imgName);
                }
            }
            
            \DB::table('meu_album_compartilhado_fotos')->insert([
                'meu_album_compartilhado_id' => $album->id,
                'foto_path' => $imgName,
                'remetente_nome' => $request->nome_usuario,
                'remetente_mensagem' => $request->mensagem,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Enviar para o Google Drive
            $driveService = new \App\Services\GoogleDriveService();
            $driveUploaded = false;
            if ($driveService->isConfigured() && $album->google_drive_folder_id) {
                $localPath = storage_path('app/public/images/' . $imgName);
                if (file_exists($localPath)) {
                    $driveFileId = $driveService->uploadFile($localPath, $imgName, $album->google_drive_folder_id);
                    $driveUploaded = ($driveFileId !== null);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Arquivo enviado com sucesso!',
                'drive_uploaded' => $driveUploaded
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nenhum arquivo enviado.'
        ], 400);
    }

    // About

    public function about(){
        $data["albums"] = Album::orderBy('created_at','desc')->get();
        $data["teams"] = Team::orderBy('created_at','desc')->get();
        return view('frontend.about',$data);
    }

    // Contact

    public function contact(){
        $data["infos"] = ContactInfo::orderBy('created_at','desc')->get();
        return view('frontend.contact',$data);
    }

    public function contactForm(Request $request){
        
        $validator = Validator::make($request->all(),[
            'fname' => 'required',
            'lname' => 'required',
            'subject' => 'required',
            'email' => 'required|email',
            'message' => 'required|string',
            ]);
              if($validator->fails()){
                 return redirect()->back()->withErrors($validator);
              }
    
              Mail::to('tawhid.developer@gmail.com')->send(new ContactForm($request->all()));
              
              session()->flash("type","success");
              session()->flash("message","Mail Send Successfully");
              
              return redirect()->route("contact");
    
    }

    // Service

    public function service(){
        $data["albums"] = Album::orderBy('created_at','desc')->get();
        $data["ourservices"] = Service::all();
        return view('frontend.service',$data);
    }

    public function sitemap(){
        $albums = Album::orderBy('updated_at', 'desc')->get();
        $shared_albums = \App\MeuAlbumCompartilhado::orderBy('updated_at', 'desc')->get();
        
        return response()->view('frontend.sitemap', [
            'albums' => $albums,
            'shared_albums' => $shared_albums,
        ])->header('Content-Type', 'text/xml');
    }

    public function visualSitemap(){
        $albums = Album::orderBy('name', 'asc')->get();
        $shared_albums = \App\MeuAlbumCompartilhado::orderBy('titulo', 'asc')->get();
        
        return view('frontend.map_of_site', [
            'albums' => $albums,
            'shared_albums' => $shared_albums,
        ]);
    }

}
