<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


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

                    $toggleClass = $user->is_active
                        ? 'bg-red-50 text-red-700 hover:bg-red-100'
                        : 'bg-green-50 text-green-700 hover:bg-green-100';

                    $toggleText = $user->is_active ? 'Deactivate' : 'Activate';

                    return '
        <div class="flex items-center justify-center gap-2">

            <button
                data-id="' . $user->id . '"
                class="toggle-status inline-flex items-center px-3 py-1.5
                       text-xs font-semibold rounded-md transition
                       ' . $toggleClass . '">
                ' . $toggleText . '
            </button>

            <button
                data-id="' . $user->id . '"
                class="delete-user inline-flex items-center px-3 py-1.5
                       text-xs font-semibold rounded-md
                       bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-700
                       transition">
                Delete
            </button>

        </div>
    ';
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
                'message' => 'Admin account cannot be deactivated.'
            ], 403);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_active
                ? 'User activated successfully.'
                : 'User deactivated successfully.'
        ]);
    }
    // ->with('success', 'status changed successfuly!');

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

    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->role === 'admin') { // adjust if needed
            return response()->json([
                'message' => 'Admin cannot be deleted.'
            ], 403);
        }

        // Prevent deleting yourself (recommended)
        if ($user->id === auth::id()) {
            return response()->json([
                'message' => 'You cannot delete your own account.'
            ], 403);
        }

        $user->delete(); // soft delete

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }
}
