<?php

namespace App\FrontOffice\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Page d'accueil du front office.
     */
    public function index(): View
    {
        return view('front.pages.home');
    }
}
