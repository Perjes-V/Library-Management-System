

<!DOCTYPE html>
<html lang="en">
    @include('partials.head')

<body>
  <div class="container-fluid position-relative d-flex p-0">
    <div class="row w-100 m-0">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="bg-secondary rounded p-4 p-sm-5 text-center">
                <h3>Reset Password</h3>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="email" name="email" class="form-control mb-3" placeholder="Email" value="{{ $email ?? old('email') }}" required>
                    <input type="password" name="password" class="form-control mb-3" placeholder="New Password" required>
                    <input type="password" name="password_confirmation" class="form-control mb-3" placeholder="Confirm Password" required>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

    @include('partials.script')
</body>
</html>

