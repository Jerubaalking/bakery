<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProductOutModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

     // primary key
     protected $table = 'product_out';

     public $primaryKey = 'id';
    
     public $timestamps = false;

     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sales_by',
        'sales_number',
        'sub_total',
        'payment_method'
       
    ];
 

}
