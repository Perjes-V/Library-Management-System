<!DOCTYPE html>
<html lang="en">
    @include('partials.head')

<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100 p-0">
        <main class="w-100">
            <div class="row w-100 m-0 justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="login-form bg-secondary rounded p-4 p-sm-5">
                        <div class="text-center mb-4">
                            <h3>Sign In</h3>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Password" required>
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                            <div class="text-center mt-3">
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                            <div class="text-center mt-2">
                                <a href="{{ route('register') }}">Don't have an account? Register</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('partials.script')
</body>
</html>
