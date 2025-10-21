@extends('layouts.app')

@section('title', 'Login - AAPP')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="col-md-5 col-lg-4">
        <div class="card" style="background-color: var(--bg-secondary); border: 1px solid var(--border-primary); border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); overflow: hidden;">
            <div class="card-body p-4" style="background-color: var(--bg-secondary);">

                <!-- Header -->
                <div class="text-center mb-4">
                    <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Welcome Back</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">Sign in to your account</p>
                </div>
                
                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: var(--accent-green); border: none; color: white; border-radius: 8px;">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: var(--accent-red); border: none; color: white; border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label" style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem;">
                            Email Address
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="your.email@example.com"
                               required 
                               autofocus
                               style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.65rem 0.75rem;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label" style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem;">
                            Password
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required
                               style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.65rem 0.75rem;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary);">
                        <label class="form-check-label" for="remember" style="color: var(--text-muted); font-size: 0.9rem;">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn" style="background-color: var(--accent-green) !important; border: none; color: white; padding: 0.65rem; font-weight: 500; border-radius: 8px;">
                            Sign In
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">
                            Don't have an account? 
                            <a href="{{ route('register') }}" style="color: var(--accent-green-light); text-decoration: none; font-weight: 500;">Create one</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        background-color: var(--bg-tertiary);
        border-color: var(--accent-green);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25);
    }
    
    .form-control::placeholder {
        color: var(--text-muted);
    }
    
    .btn-primary:hover {
        background-color: var(--accent-green-hover) !important;
    }
    
    .form-check-input:checked {
        background-color: var(--accent-green);
        border-color: var(--accent-green);
    }
    
    .form-check-input:focus {
        border-color: var(--accent-green);
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25);
    }
</style>
@endsection