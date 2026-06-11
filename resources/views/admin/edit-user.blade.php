@extends('layouts.app')

@section('title', 'Edit Admin Credentials')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Admin Credentials</h1>
    </div>

    <div class="card" style="max-width: 560px;">
        <div class="card-header">Update account details</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <input id="password" name="password" type="password" class="form-control"
                        placeholder="Leave blank to keep the current password">
                    <small class="text-muted">Only fill this if you want to change the password.</small>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
