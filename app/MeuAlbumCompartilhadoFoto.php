<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeuAlbumCompartilhadoFoto extends Model
{
    protected $table = 'meu_album_compartilhado_fotos';
    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo('App\MeuAlbumCompartilhado', 'meu_album_compartilhado_id');
    }
}
