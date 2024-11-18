<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function showAboutPage()
    {
        return view('pages.about'); // Assuming you have a view named 'faq.blade.php'
    }
}