<?php
namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Testimonial;
use App\Models\Evenement;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEvents = Evenement::where('statut', 'publier')
            ->latest()
            ->take(3)
            ->get();
            
        $categories = category::all();
        
        $testimonials = Testimonial::latest()
            ->take(4)
            ->get();

        return view('home', compact('featuredEvents', 'categories', 'testimonials'));
    }
}