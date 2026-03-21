

<!DOCTYPE html>
<html lang="en">
    @include('partials.head')

<body>
   <div class="container-fluid position-relative d-flex p-0 mt-5">
    <div class="row w-100 m-0">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="register-form bg-secondary rounded p-4 p-sm-5">
                <div class="text-center mb-4">
                    <h3>Sign Up</h3>
                </div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}">Already have an account? Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    @include('partials.script')
</body>
</html>

