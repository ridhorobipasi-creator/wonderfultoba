<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $transactions = Booking::where('status', 'confirmed')
            ->latest('createdAt')
            ->paginate(20);
            
        return view('admin.finance.index', compact('transactions'));
    }
}
