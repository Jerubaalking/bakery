<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProductModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

     // primary key
     protected $table = 'products';

     public $primaryKey = 'id';
    
     public $timestamps = false;

     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'batch_number',
        'product_name',
        'stock'
       
    ];
 

}
