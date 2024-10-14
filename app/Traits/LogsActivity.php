<?php
namespace App\Traits;

use App\Models\ActivityModel;

trait LogsActivity
{
    public function logActivity($action, $status, $output = null)
    {
        ActivityModel::create([
            'action' => $action,
            'done_by' => auth()->user()->name ?? null,  // Use the authenticated user or set to null
            'ip' => request()->ip(),
            'status' => $status,
            'agent' => request()->header('User-Agent'),
            'output' => $output,
        ]);
    }
}

?>