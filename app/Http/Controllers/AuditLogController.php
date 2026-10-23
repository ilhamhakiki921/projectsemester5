<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()->orderByDesc('created_at')->orderByDesc('id');

        if ($request->filled('aksi') && array_key_exists($request->aksi, ActivityLog::AKSI)) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->where(function ($w) use ($like) {
                $w->where('username', 'like', $like)
                  ->orWhere('keterangan', 'like', $like)
                  ->orWhere('no_rm', 'like', $like)
                  ->orWhere('ip_address', 'like', $like);
            });
        }

        return view('audit-log.index', [
            'logs'     => $query->paginate(20)->withQueryString(),
            'daftarAksi' => ActivityLog::AKSI,
        ]);
    }
}
