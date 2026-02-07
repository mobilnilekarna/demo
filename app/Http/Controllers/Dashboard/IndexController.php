<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-dashboard');
    }

    public function index()
    {
        $this->authorize('view-dashboard');

        return Inertia::render('Dashboard/Index', [
            'message' => 'Vítejte v Laravel + Vue.js + Inertia.js aplikaci!',
            'user' => auth()->user(),
        ]);
    }

    public function login()
    {
        return Inertia::render('Dashboard/Login', [
            'message' => 'Vítejte v Laravel + Vue.js + Inertia.js aplikaci!',
        ]);
    }
}
