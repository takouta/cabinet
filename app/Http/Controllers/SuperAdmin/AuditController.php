<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Modules\Auth\Models\User;

class AuditController extends Controller
{
    public function index()
    {
        $logs = AuditLog::latest()->paginate(20);

        $logs->getCollection()->transform(function ($log) {
            $log->user = $log->user_id ? User::find($log->user_id) : null;
            return $log;
        });

        return view('super_admin.audits.index', compact('logs'));
    }
}
