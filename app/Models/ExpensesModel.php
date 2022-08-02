<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ExpensesModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

     // primary key
     protected $table = 'expensive';

     public $primaryKey = 'id';
    
     public $timestamps = false;

     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'description',
        'amount',
        'expensive_date'
       
    ];
 

}
