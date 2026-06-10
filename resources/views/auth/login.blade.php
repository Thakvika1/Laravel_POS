@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="card" style="max-width:480px;margin:48px auto;">
        <div class="card-header">Admin Login</div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}"
                        required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                    <input id="remember" type="checkbox" name="remember" value="1">
                    <label for="remember" class="text-muted">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
@endsection
