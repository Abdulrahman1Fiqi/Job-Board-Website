<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;

class DashboardController extends Controller
{
    public function index()
    {
        $jobs = JobVacancy::query()->latest()->paginate(10)->withQueryString();
        return view('dashboard', compact('jobs'));   
    }
}
