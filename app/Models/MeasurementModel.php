<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MeasurementModel extends Model implements Auditable
{
    use HasFactory;
    protected $table="measurements";
    use \OwenIt\Auditing\Auditable;

    public $primaryKey = 'id';
    
    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'measurement',
        'symbol',
        'type',
        'description',
    ];

}
