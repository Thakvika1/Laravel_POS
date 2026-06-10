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
}
