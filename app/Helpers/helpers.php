<?php

use Illuminate\Support\Facades\DB;

function photon_notification($errors){
    
    if(session()->has('message')):
        echo '<div class="alert alert-'.session()->get('type').' alert-dismissible fade show" role="alert">'.session()->get('message').'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    endif;

    if(session()->has('status')):
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.session()->get('status').'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    endif;

    if($errors->any()):
    echo '<div class="alert alert-danger">';
      foreach ($errors->all() as $error):
            echo '<li>'. $error .'</li>';
      endforeach;
    echo '</div>';
    endif;


}



// Image Link

function photon_thumbnail($name){

    $link = substr($name,0,7);

    if($link === "https:/" || $link === "http://" ){
        return $name;
    }elseif($name === null){
        return asset('storage/images/default.jpg');

    }
    return asset('storage/images/'.$name);
}


function photon_image_process($request, $name){
    // Check if there is a cropped version (Base64 from Cropper.js)
    $croppedName = 'cropped_' . $name;
    if ($request->has($croppedName) && !empty($request->$croppedName)) {
        $data = $request->$croppedName;
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);
            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }

            $imgName = sprintf('%s.%s', str_random(10), $type);
            \Storage::disk('public')->put('images/' . $imgName, $data);
            return $imgName;
        }
    }
    
    if($request->hasFile($name)){

        $validator = validator()->make($request->all(),[
            $name  => 'image',
       ]);

        if($validator->fails()){

            return redirect()->back()->withErrors($validator);
        }


        $imgName = sprintf('%s.%s',str_random(10),$request->$name->extension());
        
        $request->$name->storeAs('images',$imgName);


    }else{

        $thumbUrl = sprintf("%s_url",$name);
           
            if($request->$thumbUrl){

                //    Thumbnail URl Process Start

                $validator = validator()->make($request->all(),[
                    $thumbUrl  => 'active_url',
               ]);
        
                if($validator->fails()){
        
                    return redirect()->back()->withErrors($validator);
                }

                
                $imgName =  $request->$thumbUrl;

                //    Thumbnail URl Process End
     
            }else{

                $imgName = 'default.jpg';

            }
    


    }

    return $imgName;

}



// Check Selected 

function photon_selected($post,$item){


    return ($post === $item ) ? 'selected' : '';
}


function setting($key){
    
    try{
        $value = DB::table('settings')->where('key',$key)->first()->value;
        return $value;
    }catch(Exception $e){
        return;
    }
}
