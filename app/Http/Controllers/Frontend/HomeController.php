<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Product;
use App\Models\Banner;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get banners by types
        $mainSliderBanners = Banner::mainSlider()->get();
        $rightTopBanner = Banner::rightTop()->first();
        $rightBottomBanner = Banner::rightBottom()->first();
        $bottomBanners = Banner::bottom()->get();
        
        // Get latest books
        $newReleases = Book::where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // Get root categories (level 1)
        $rootCategories = Category::where('level', 1)
            ->with(['children' => function($query) {
                $query->with('children'); // Load level 3 categories
            }])
            ->take(6)
            ->get();
        
        // Get popular authors
        $authors = Author::take(6)->get();
        
        // Get latest products (đồ chơi và dụng cụ học tập)
        $products = Product::where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        return view('frontend.home', compact(
            'newReleases', 
            'rootCategories', 
            'authors', 
            'products', 
            'mainSliderBanners', 
            'rightTopBanner', 
            'rightBottomBanner', 
            'bottomBanners'
        ));
    }
    
    /**
     * Display the about page.
     */
    public function about()
    {
        return view('frontend.about');
    }
    
    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('frontend.contact');
    }
    
    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Here you would typically send an email or store the contact message
        
        return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
    
    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        return view('frontend.faq');
    }
    
    /**
     * Display the terms page.
     */
    public function terms()
    {
        return view('frontend.terms');
    }
    
    /**
     * Display the privacy page.
     */
    public function privacy()
    {
        return view('frontend.privacy');
    }
}
