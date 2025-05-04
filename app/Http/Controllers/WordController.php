<?php

namespace App\Http\Controllers;

use App\Models\Category; 
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // 獲取所有類別
        return view('staff.reporter.word', compact('categories')); // 傳遞 $categories 到視圖
    }
}
