<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashMovement;
use App\Models\CashStatus;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $query = CashMovement::with('cashStatus.user', 'sale');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('user_id')) {
            $query->whereHas('cashStatus', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(20);

        $totalEntries = $movements->sum(function ($movement) {
            return $movement->type === 'entry' ? $movement->amount : 0;
        });

        $totalExits = $movements->sum(function ($movement) {
            return $movement->type === 'exit' ? $movement->amount : 0;
        });

        $netBalance = $totalEntries - $totalExits;

        return view('cash_flow.index', compact('movements', 'totalEntries', 'totalExits', 'netBalance'));
    }
}
