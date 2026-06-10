@extends('layouts.app')

@section('title', 'Admin Accounts')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Admin Accounts</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <span class="badge badge-purple">{{ $admins->count() }} admin{{ $admins->count() === 1 ? '' : 's' }}</span>
                <span class="text-muted">System admins can force logout or remove other admins from the system.</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($admins->isEmpty())
                <p class="text-muted">No admin accounts found.</p>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        @if ($admin->is_system_admin)
                                            <span class="badge badge-green">System Admin</span>
                                        @else
                                            <span class="badge badge-purple">Admin</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->created_at ? $admin->created_at->format('M d, Y') : '—' }}</td>
                                    <td>
                                        @if (Auth::user()?->is_system_admin && $admin->id !== Auth::id())
                                            <div class="flex gap-2">
                                                <form method="POST" action="{{ route('admin.users.logout', $admin) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.destroy', $admin) }}"
                                                    onsubmit="return confirm('Delete this admin account?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">You</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-4" style="max-width:560px;">
        <div class="card-header">Create New Admin</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Admin</button>
            </form>
        </div>
    </div>
@endsection
