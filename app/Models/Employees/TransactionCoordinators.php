<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCoordinators extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    public $table = 'emp_transaction_coordinators';
    protected $guarded = [];

}
