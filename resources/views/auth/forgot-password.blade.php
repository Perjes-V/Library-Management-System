<!DOCTYPE html>
<html lang="en">
    @include('partials.head')

<body>
    <div class="d-flex align-items-center justify-content-center vh-100 bg-light">
        <div class="col-12 col-sm-8 col-md-5 col-lg-4">
            <div class="card shadow-lg rounded-4 p-4 p-sm-5 text-center">
                <h3 class="mb-3">Forgot Password</h3>
                <p class="mb-4 text-muted">Enter your email to reset your password.</p>

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3 text-start">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control rounded-3" placeholder="Enter your email" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">
                        Send Password Reset Link
                    </button>

                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">Back to Log In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.script')
</body>
</html>