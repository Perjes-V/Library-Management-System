<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark flex-column">

        {{-- BRAND --}}
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>LBMSystem</h3>
        </a>

        <ul class="navbar-nav w-100">

            {{-- DASHBOARD --}}
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" 
                   class="nav-link text-white fontsize-5 ajaxLink {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            {{-- BOOKS --}}
            @php $booksOpen = request()->routeIs('books.*'); @endphp
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center py-1" 
                   data-bs-toggle="collapse" href="#booksSubmenu" role="button" aria-expanded="{{ $booksOpen ? 'true' : 'false' }}">
                    <span><i class="bi bi-book me-2"></i> Books</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul class="collapse list-unstyled ps-3 {{ $booksOpen ? 'show' : '' }}" id="booksSubmenu">
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('books.index') ? 'active' : '' }}" 
                           href="{{ route('books.index') }}">• Books</a>
                    </li>
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('books.create') ? 'active' : '' }}" 
                           href="{{ route('books.create') }}">• Add Book</a>
                    </li>
                </ul>
            </li>

            {{-- STUDENTS --}}
            @php $studentsOpen = request()->routeIs('students.*'); @endphp
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center py-1" 
                   data-bs-toggle="collapse" href="#studentsSubmenu" role="button" aria-expanded="{{ $studentsOpen ? 'true' : 'false' }}">
                    <span><i class="bi bi-people me-2"></i> Students</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul class="collapse list-unstyled ps-3 {{ $studentsOpen ? 'show' : '' }}" id="studentsSubmenu">
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('students.index') ? 'active' : '' }}" 
                           href="{{ route('students.index') }}">• Students</a>
                    </li>
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('students.create') ? 'active' : '' }}" 
                           href="{{ route('students.create') }}">• Add Student</a>
                    </li>
                </ul>
            </li>

            {{-- BORROW --}}
            @php $borrowOpen = request()->routeIs('borrow_transactions.*') || request()->routeIs('borrow_transactions.returned'); @endphp
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center py-1" 
                   data-bs-toggle="collapse" href="#borrowSubmenu" role="button" aria-expanded="{{ $borrowOpen ? 'true' : 'false' }}">
                    <span><i class="bi bi-arrow-down-square me-2"></i> Borrow</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul class="collapse list-unstyled ps-3 {{ $borrowOpen ? 'show' : '' }}" id="borrowSubmenu">
                    
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('borrow_transactions.create') ? 'active' : '' }}" 
                           href="{{ route('borrow_transactions.create') }}">• Borrow Book</a>
                    </li>
                    
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('borrow_transactions.index') ? 'active' : '' }}" 
                           href="{{ route('borrow_transactions.index') }}">• Borrowed Books</a>
                    </li>
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('borrow_transactions.returned') ? 'active' : '' }}" 
                           href="{{ route('borrow_transactions.returned') }}">• Returned Books</a>
                    </li>
                </ul>
            </li>

            {{-- CATEGORIES --}}
            @php $categoriesOpen = request()->routeIs('categories.*'); @endphp
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center py-1" 
                   data-bs-toggle="collapse" href="#categoriesSubmenu" role="button" aria-expanded="{{ $categoriesOpen ? 'true' : 'false' }}">
                    <span><i class="bi bi-tags-fill me-2"></i> Categories</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul class="collapse list-unstyled ps-3 {{ $categoriesOpen ? 'show' : '' }}" id="categoriesSubmenu">
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('categories.index') ? 'active' : '' }}" 
                           href="{{ route('categories.index') }}">• Categories</a>
                    </li>
                    <li>
                        <a class="nav-link text-white py-1 ajaxLink {{ request()->routeIs('categories.create') ? 'active' : '' }}" 
                           href="{{ route('categories.create') }}">• Add Category</a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</div>
<!-- Sidebar End -->