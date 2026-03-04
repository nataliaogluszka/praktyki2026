<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\AuditLog::with('user')->latest()->paginate(20);

        return view('logs.index', compact('logs'));
    }

    public function create()
    {
        //
    }
}