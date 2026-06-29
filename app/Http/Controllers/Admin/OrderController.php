<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $query = Order::query();
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('customer', fn(Order $o) => '<p class="font-bold text-slate-800">'.$o->customer_name.'</p><p class="text-slate-400 text-xs mt-0.5">'.$o->customer_email.'</p>')
                ->editColumn('created_at', fn(Order $o) => $o->created_at->format('M d, Y h:i A'))
                ->addColumn('total_formatted', fn(Order $o) => '$'.number_format($o->total, 2))
                ->addColumn('status_badge', fn(Order $o) => '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider '.$o->status_color.'">'.$o->status.'</span>')
                ->addColumn('payment_badge', fn(Order $o) => '<span class="px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider '.($o->payment_status === 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200').'">'.$o->payment_status.'</span>')
                ->addColumn('action', fn(Order $o) => '<a href="'.route('admin.orders.show', $o->id).'" class="px-3 py-1.5 border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition-colors inline-block">View Details</a>')
                ->rawColumns(['customer', 'status_badge', 'payment_badge', 'action'])
                ->make(true);
        }
        return view('admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
