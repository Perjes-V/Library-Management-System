<!DOCTYPE html>
<html lang="en">
    @include('partials.head')

<body class="d-flex flex-column min-vh-100">

    <div class="container-fluid d-flex flex-grow-1 p-0">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main content area --}}
        <div class="content flex-grow-1 d-flex flex-column">

            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Page Content --}}
            <main class="flex-grow-1">
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('partials.footer')
        </div>
       
    </div>

    @include('partials.script')
</body>
</html>