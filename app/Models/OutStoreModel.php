<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class OutStoreModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

     // primary key
     protected $table = 'out_store';

     public $primaryKey = 'id';
    
     public $timestamps = false;

     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'material_id',
        'measurement_id',
        'production_session_id',
        'qty',
        'batch_number',
        'receipt',
        'comment',
        'dty_per_unit',
        'date'
       
    ];
 

}
