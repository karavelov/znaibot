<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function login() {

        return view('admin.auth.login');
    }




    public function dashboard() {
        

        $totalBrands = Brand::count();

        $totalUsers = User::where('role', 'admin')->count();
        $totalBlogs=Blog::where('status',1)->count();

        return view('admin.dashboard', compact('totalBlogs', 'totalBrands', 'totalUsers'));
    }
}
