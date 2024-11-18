<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function showHelpPage()
    {
        return view('pages.faq'); // Assuming you have a view named 'faq.blade.php'
    }
}
