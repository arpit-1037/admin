<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = User::query()->select('users.*');

            return DataTables::eloquent($query)
                ->addIndexColumn()

                ->addColumn('status', function ($user) {
                    return $user->is_active
                        ? '<span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>';
                })

                ->addColumn('registered_on', function ($user) {
                    return $user->created_at->format('d M Y');
                })

                ->addColumn('actions', function ($user) {
                    return '
           <button
            data-id="' . $user->id . '"
            class="toggle-status px-3 py-1 text-xs font-semibold rounded
                   ' . ($user->is_active ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') . '">
            ' . ($user->is_active ? 'Deactivate' : 'Activate') . '
           </button>';
                })
                ->rawColumns(['status', 'actions'])
                ->addColumn('status', function ($user) {
                    return $user->is_active
                        ? '<span class="status-badge px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>'
                        : '<span class="status-badge px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>';
                })

                ->toJson();
        }

        return view('admin.users.index');
    }
    public function toggleStatus(User $user)
    {
        // Prevent admin from deactivating self
        // if (auth()->id() === $user->id) {
        //     return response()->json([
        //         'message' => 'You cannot deactivate your own account.'
        //     ], 403);
        // }

        // ❌ Do not allow admin accounts to be deactivated
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Admin accounts cannot be deactivated.'
            ], 403);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return response()->json([
            'status' => $user->is_active,
            'label'  => $user->is_active ? 'Active' : 'Inactive'
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
}
