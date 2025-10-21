@extends('layouts.app')

@section('title', 'Register - AAPP')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="col-md-5 col-lg-4">
        <div class="card" style="background-color: var(--bg-secondary); border: 1px solid var(--border-primary); border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); overflow: hidden;">
            <div class="card-body p-4" style="background-color: var(--bg-secondary);">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Create Account</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">Start your exam preparation journey</p>
                </div>
                
                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: var(--accent-red); border: none; color: white; border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Please fix the following errors:
                        <ul class="mb-0 mt-2" style="font-size: 0.9rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label" style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem;">
                            Full Name
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter your full name"
                               required
                               style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.5rem 0.75rem;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                               style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.5rem 0.75rem;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Class Selection -->
                    <div class="mb-3">
                        <label for="class" class="form-label" style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem;">
                            Class
                        </label>
                        <select class="form-select @error('class') is-invalid @enderror" 
                                id="class" 
                                name="class" 
                                required
                                style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.5rem 0.75rem;">
                            <option value="" selected disabled>Select your class</option>
                            <option value="SSC" {{ old('class') == 'SSC' ? 'selected' : '' }}>SSC</option>
                            <option value="HSC" {{ old('class') == 'HSC' ? 'selected' : '' }}>HSC</option>
                        </select>
                        @error('class')
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
                               placeholder="Minimum 8 characters"
                               required
                               style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.5rem 0.75rem;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label" style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem;">
                            Confirm Password
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Re-enter your password"
                               required
                               style="background-color: var(--bg-tertiary); border: 1px solid var(--border-primary); color: var(--text-primary); border-radius: 8px; padding: 0.5rem 0.75rem;">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary" style="background-color: var(--accent-green); border: none; color: white; padding: 0.65rem; font-weight: 500; border-radius: 8px;">
                            Create Account
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">
                            Already have an account? 
                            <a href="{{ route('login') }}" style="color: var(--accent-green-light); text-decoration: none; font-weight: 500;">Sign in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        background-color: var(--bg-tertiary);
        border-color: var(--accent-green);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25);
    }
    
    .form-control::placeholder {
        color: var(--text-muted);
    }
    
    .form-select option {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }
    
    .btn-primary:hover {
        background-color: var(--accent-green-hover) !important;
    }
</style>
@endsection