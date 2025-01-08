<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

// use OwenIt\Auditing\Auditable;

class Spending extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    
     // primary key
     protected $table = 'spendings';

     public $primaryKey = 'id';
    
     public $timestamps = true;

     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'receipt',
        'category',
        'amount',
        'date'
    ];
}
