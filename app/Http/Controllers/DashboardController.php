<?php

namespace App\Http\Controllers;

use App\Models\Pasien;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPasien = Pasien::count();

        return view('dashboard', compact(
            'totalPasien'
        ));
    }
}