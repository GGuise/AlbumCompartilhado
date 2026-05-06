<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeuAlbumCompartilhado extends Model
{
    protected $table = 'meu_album_compartilhados';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fotos()
    {
        return $this->hasMany('App\MeuAlbumCompartilhadoFoto', 'meu_album_compartilhado_id');
    }
}
