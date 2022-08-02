<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SalesModel extends Model implements Auditable
{
    use HasFactory;
    protected $table="sales";
    use \OwenIt\Auditing\Auditable;

    public $primaryKey = 'id';
    
    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'product_id',
        'qty',
        'price',
        'amt',
    ];

}
