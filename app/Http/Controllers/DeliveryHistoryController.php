<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryHistory;

class DeliveryHistoryController extends Controller
{
    public function index()
    {
        $histories = DeliveryHistory::with(['deliveryPerson', 'order'])->orderBy('created_at', 'desc')->paginate(10);
        return view('delivery_history.index', compact('histories'));
    }

    public function show(DeliveryHistory $deliveryHistory)
    {
        return view('delivery_history.show', compact('deliveryHistory'));
    }
}
