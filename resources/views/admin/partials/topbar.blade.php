<header class="ad-top"><button class="ad-ico d-lg-none" id="sideToggle" type="button" aria-label="Menu"><svg class="ic "
            width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 6h18M3 12h18M3 18h18" />
        </svg></button><button class="ad-ico d-none d-lg-inline-flex" id="sideCollapse" type="button"
        aria-label="Collapse sidebar"><svg class="ic " width="20" height="20" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <path d="M3 6h18M3 12h18M3 18h18" />
        </svg></button>
    <form class="ad-search" role="search"><svg class="ic " width="16" height="16" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <circle cx="11" cy="11" r="7" />
            <path d="M20 20l-4-4" />
        </svg><input type="search" placeholder="Search bookings, guests, rooms…" aria-label="Search"></form>
    <div class="ms-auto d-flex align-items-center gap-2"><a class="btn-ad btn-ad--sm d-none d-md-inline-flex"
            href="{{ url('admin/bookings') }}#new"><svg class="ic " width="14" height="14" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="M12 5v14M5 12h14" />
            </svg> New booking</a>
        <div class="dropdown"><button class="ad-ico" data-bs-toggle="dropdown" aria-label="Notifications"><svg
                    class="ic " width="19" height="19" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M6 17V11a6 6 0 0112 0v6l2 2H4zM10 21h4" />
                </svg><span class="ad-dot"></span></button>
            <div class="dropdown-menu dropdown-menu-end ad-drop">
                <h6 class="dropdown-header">Notifications</h6><a class="dropdown-item"
                    href="{{ url('admin/bookings') }}"><strong>New booking</strong> #BK-1048 — Presidential
                    Suite<small>2 min ago</small></a><a class="dropdown-item"
                    href="{{ url('admin/payments') }}"><strong>Payment received</strong> $780 from R. Dan<small>1 hr
                        ago</small></a><a class="dropdown-item" href="{{ url('admin/reviews') }}"><strong>New
                        review</strong> 5★ from Demeail S.<small>3 hrs ago</small></a>
            </div>
        </div>
        <div class="dropdown"><button class="ad-user" data-bs-toggle="dropdown"><span class="av">AD</span><span
                    class="d-none d-md-block text-start"><strong>{{ auth()->user()->name }}</strong><small>Super
                        Admin</small></span></button>
                    
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ url('/settings/two-factor') }}">Profile &amp; settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                 <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="dropdown-item" type="submit">Logout</button>
</form>

@if (session('status'))
    <p style="color: green;">
        {{ session('status') }}
    </p>
@endif

@if (session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
@endif 
</li>
            </ul>
        </div>
    </div>
</header>
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
