<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityModel extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'action', 'done_by', 'ip', 'status', 'agent', 'output'
    ];
}

?>