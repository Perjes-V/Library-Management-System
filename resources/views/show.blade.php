<!DOCTYPE html>
<html lang="en">
    @include('partials.head')
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow rounded">
                    <!-- Header with Back Arrow -->
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none me-3 fs-4">&larr;</a>
                        <h3 class="mb-0 text-center flex-grow-1">My Profile</h3>
                    </div>

                    <!-- Profile Image -->
                    <div class="d-flex justify-content-center mt-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff&size=120" 
                             alt="Profile Picture" class="rounded-circle border border-3 border-white shadow">
                    </div>

                    <!-- Card Body -->
                    <div class="card-body text-center mt-3">
                        <!-- User Name -->
                        <h4 class="card-title">{{ $user->name }}</h4>
                        <!-- Email -->
                        <p class="text-muted mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <!-- Member Since -->
                        <p class="text-muted"><strong>Member since:</strong> {{ $user->created_at->format('F Y') }}</p>
                    </div>

                </div>

            </div>
        </div>
    </div>
    @include('partials.script')
</body>
</html>