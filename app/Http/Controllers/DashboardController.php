<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller
{
    public function index(){
        return view('users.dashboard');
    }
}
