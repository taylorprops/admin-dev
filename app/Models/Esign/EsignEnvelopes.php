<?php

namespace App\Models\Esign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EsignEnvelopes extends Model
{

    use SoftDeletes;

    public $table = 'esign_envelopes';
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function boot() {
        parent::boot();
        static::addGlobalScope(function ($query) {
            if(auth() -> user()) {
                if(auth() -> user() -> group == 'agent') {
                    $query -> where('User_ID', auth() -> user() -> id);
                } else if(auth() -> user() -> group == 'admin') {
                    $query -> where('User_ID', auth() -> user() -> id)
                        -> orWhere('is_system_template', 'yes')
                        -> orWhere('is_admin_template', 'yes');
                }
            }
        });
    }

    public function documents() {
        return $this -> hasMany('App\Models\Esign\EsignDocuments', 'envelope_id', 'id');
    }

    public function signers() {
        return $this -> hasMany('App\Models\Esign\EsignSigners', 'envelope_id', 'id');
    }

    public function fields() {
        return $this -> hasMany('App\Models\Esign\EsignFields', 'envelope_id', 'id');
    }

    public function callbacks() {
        return $this -> hasMany('App\Models\Esign\EsignCallbacks', 'related_document_hash', 'document_hash');
    }

    public function listing() {

        return $this -> hasOne('App\Models\DocManagement\Transactions\Listings\Listings', 'Listing_ID', 'Listing_ID') -> where('Listing_ID', '>', 0);

    }

    public function contract() {

        return $this -> hasOne('App\Models\DocManagement\Transactions\Contracts\Contracts', 'Contract_ID', 'Contract_ID') -> where('Contract_ID', '>', 0);

    }

    public function referral() {

        return $this -> hasOne('App\Models\DocManagement\Transactions\Referrals\Referrals', 'Referral_ID', 'Referral_ID') -> where('Referral_ID', '>', 0);

    }

}
