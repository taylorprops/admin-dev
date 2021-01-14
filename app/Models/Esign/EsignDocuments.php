<?php

namespace App\Models\Esign;

use Illuminate\Database\Eloquent\Model;

class EsignDocuments extends Model
{
    public $table = 'esign_documents';
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function images() {
        return $this -> hasMany('App\Models\Esign\EsignDocumentsImages', 'document_id', 'id');
    }

    public function fields() {
        return $this -> hasMany('App\Models\Esign\EsignFields', 'document_id', 'id');
    }

}
