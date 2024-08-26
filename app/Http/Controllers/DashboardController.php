<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){

        $posts=Auth::user()->posts()->latest()->paginate(6);
        
        return view('users.dashboard',['posts'=>$posts]);
    }
}
