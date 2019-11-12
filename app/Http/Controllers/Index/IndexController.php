<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Review;

class IndexController extends Controller
{
    //

    /**
     * Show the index page of the website, fetches dynamic content
     */
    public function showIndex()
    {
        // Get Reviews
        $reviews = Review::where('deleted', false)->get();

        return view('welcome', [
            'reviews' => $reviews
        ]);

    }
}
