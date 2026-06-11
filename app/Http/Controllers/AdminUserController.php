<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureAuthenticated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureAuthenticated::class);
    }

    public function index()
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        abort_unless(Auth::user()?->is_admin, 403);

        $admins = User::where('is_admin', true)->latest()->get();

        return view('admin.users', compact('admins'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        abort_unless(Auth::user()?->is_admin, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
            'is_system_admin' => false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin account created successfully.');
    }

    public function edit(User $user)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $this->authorizeAdminUpdate($user);

        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $this->authorizeAdminUpdate($user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $updates = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $updates['password'] = Hash::make($data['password']);
        }

        $user->forceFill($updates)->save();

        return redirect()->route('admin.users.index')->with('success', 'Admin credentials updated successfully.');
    }

    public function logoutUser(User $user)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        abort_unless(Auth::user()?->is_system_admin, 403);
        abort_if($user->id === Auth::id(), 403);

        if ($user->session_id) {
            Session::getHandler()->destroy($user->session_id);
        }

        $user->forceFill(['session_id' => null])->save();

        return back()->with('success', 'Admin logged out successfully.');
    }

    public function destroy(User $user)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        abort_unless(Auth::user()?->is_system_admin, 403);
        abort_if($user->id === Auth::id(), 403);

        $user->delete();

        return back()->with('success', 'Admin deleted successfully.');
    }

    protected function authorizeAdminUpdate(User $user): void
    {
        abort_unless(Auth::user()?->is_admin, 403);

        $currentUser = Auth::user();

        if (! $currentUser?->is_system_admin && $currentUser?->id !== $user->id) {
            abort(403);
        }
    }
}
