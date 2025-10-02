@extends('layouts.app')

@section('title', 'Register - AAPP')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="auth-card">
            <div class="auth-header">
                <i class="bi bi-person-plus-fill" style="font-size: 2.5rem;"></i>
                <h2 class="mt-2">Create Account</h2>
                <p class="mb-0">Join AAPP to start your exam preparation</p>
            </div>
            
            <div class="auth-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Oops!</strong> Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>Full Name
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter your full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email Address
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="your.email@example.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Class Selection -->
                    <div class="mb-3">
                        <label for="class" class="form-label">
                            <i class="bi bi-book me-1"></i>Class
                        </label>
                        <select class="form-select @error('class') is-invalid @enderror" 
                                id="class" 
                                name="class" 
                                required>
                            <option value="" selected disabled>Select your class</option>
                            <option value="SSC" {{ old('class') == 'SSC' ? 'selected' : '' }}>SSC</option>
                            <option value="HSC" {{ old('class') == 'HSC' ? 'selected' : '' }}>HSC</option>
                        </select>
                        @error('class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Board (Optional) -->
                    <div class="mb-3">
                        <label for="board" class="form-label">
                            <i class="bi bi-building me-1"></i>Board <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('board') is-invalid @enderror" 
                               id="board" 
                               name="board" 
                               value="{{ old('board') }}" 
                               placeholder="e.g., Dhaka, Rajshahi, Chittagong">
                        @error('board')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>Password
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Minimum 8 characters"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>Confirm Password
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Re-enter your password"
                               required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-check me-2"></i>Register
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection