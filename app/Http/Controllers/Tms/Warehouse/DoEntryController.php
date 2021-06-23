<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\DOEntryTrait;
use Illuminate\Http\Request;

class DoEntryController extends Controller
{
    use DOEntryTrait;
    
    public function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        
    }

    public function create(Request $request)
    {
        
    }

    public function update(Request $request)
    {
        
    }

    public function headerTools(Request $request)
    {
        
    }
}
