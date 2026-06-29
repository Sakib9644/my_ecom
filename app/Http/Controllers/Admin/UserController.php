<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $users = User::query();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role_badge', fn(User $u) => $u->is_admin
                    ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100 uppercase tracking-wider">Administrator</span>'
                    : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase tracking-wider">Customer</span>')
                ->editColumn('created_at', fn(User $u) => $u->created_at->format('M d, Y h:i A'))
                ->rawColumns(['role_badge'])
                ->make(true);
        }
        return view('admin.users.index');
    }
}
