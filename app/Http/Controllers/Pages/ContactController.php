<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use App\Rules\Recaptcha;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function index(){
        return Inertia::render('Contact');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:255',
            'recaptcha' => ['required', new Recaptcha()],
        ]);

        // TODO: odeslat e-mail nebo uložit do DB
        return response()->json([
            'message' => __('Formulář byl úspěšně odeslán'),
        ]);
    }
}
