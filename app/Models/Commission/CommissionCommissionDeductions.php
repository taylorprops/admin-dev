<?php

namespace App\Models\Commission;

use Illuminate\Database\Eloquent\Model;

class CommissionCommissionDeductions extends Model
{
    public $table = 'commission_commission_deductions';
    protected $_connection = 'mysql';
    protected $_primaryKey = 'id';
    protected $guarded = [];
}