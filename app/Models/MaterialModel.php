<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MaterialModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

     // primary key
     protected $table = 'materials';

     public $primaryKey = 'id';
    
     public $timestamps = false;

     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'measurement_id',
        'material_category_id',
        'name',
        'dty_per_unit',
        'qty_cost',
        'unit_cost',
       
    ];
 

}
