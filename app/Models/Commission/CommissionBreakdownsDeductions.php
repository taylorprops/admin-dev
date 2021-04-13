<?php

namespace App\Models\Commission;

use Illuminate\Database\Eloquent\Model;

class CommissionBreakdownsDeductions extends Model
{
    protected $table = 'commission_breakdowns_deductions';
    protected $_connection = 'mysql';
    protected $_primaryKey = 'id';
    protected $guarded = [];
}
