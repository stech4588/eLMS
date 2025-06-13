<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $title = $request->query('title');
        $price = $request->query('price');

        // You might want to validate that the course exists and the price is correct
        // For now, we'll pass them directly to the view.

        return Inertia::render('cart/cart', [
            'course_id' => $courseId,
            'title' => $title,
            'price' => $price,
        ]);
    }
}
