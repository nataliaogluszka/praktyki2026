<?php

namespace App\Http\Controllers;

use DragonCode\Contracts\Cashier\Config\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class LogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);

        $lowStockItems = \App\Models\Inventory::with('product')
            ->where('quantity', '<', 50)
            ->get();

        return view('logs.index', compact('logs', 'lowStockItems'));
    }

    public function create()
    {
        //
    }
}