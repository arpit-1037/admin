<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserService;


class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // Keeping DataTables Eloquent query as-is (minimal change)
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
        <a href="' . route('admin.users.edit', $user->id) . '"
   class="inline-flex items-center px-3 py-1.5
          text-xs font-semibold rounded-md
          bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
    Edit
</a>

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
                ->toJson();
        }

        return view('admin.users.index');
    }

    public function toggleStatus(User $user)
    {
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

        $this->userRepository->create([
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
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Admin cannot be deleted.'
            ], 403);
        }

        if ($user->id === Auth::id()) {
            return response()->json([
                'message' => 'You cannot delete your own account.'
            ], 403);
        }

        $this->userRepository->delete($user);

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $this->userRepository->update($user, $data);

        return redirect()->route('admin.users.index')
            ->with('user_updated', 'User updated successfully.');
    }
}
