<?php

namespace App\Http\Controllers\Tms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tms_HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tms.home');
    }

    public function test()
    {
        return view('tms.test');
    }


}
