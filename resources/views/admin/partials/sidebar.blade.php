<aside class="ad-side" id="adSide">
    <a class="ad-logo" href="{{ route('dashboard') }}"><span class="ad-logo__n">PATARA</span><span
            class="ad-logo__s">ADMIN PANEL</span></a>
    <nav class="ad-nav" aria-label="Admin">
        <p class="ad-nav__g">Main</p>
        <ul>
                <li><a class="ad-nav__a {{ ($active ?? '') == 'dashboard' ? 'active' : '' }}"
                        href="{{ route('dashboard') }}"><svg class="ic " width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                        </svg><span>Dashboard</span></a></li>
            @can('view bookings')
            <li><a class="ad-nav__a {{ ($active ?? '') == 'bookings' ? 'active' : '' }}"
                    href="{{ route('admin.bookings.index') }}"><svg class="ic " width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="16" rx="1" />
                        <path d="M3 10h18M8 3v4M16 3v4" />
                    </svg><span>Bookings</span></a></li>
        @endcan
        @can('view bookings')
        <li><a class="ad-nav__a {{ ($active ?? '') == 'calendar' ? 'active' : '' }}"
                href="{{ route('admin.calendar.index') }}"><svg class="ic " width="18" height="18"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 18V8h18v10M3 14h18M6 11h5v3H6zM13 11h5v3h-5z" />
                </svg><span>Availability Calendar</span></a></li>
    @endcan
    @can('manage rooms')
    <li><a class="ad-nav__a {{ ($active ?? '') == 'rooms' ? 'active' : '' }}" href="{{ route('admin.room-types.index') }}"><svg
                class="ic " width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <circle cx="8" cy="15" r="4" />
                <path d="M11 12l9-9M16 7l3 3" />
            </svg><span>Room Management</span></a></li>
@endcan
</ul>
<p class="ad-nav__g">Operations</p>
<ul>
@can('view guests')
    <li><a class="ad-nav__a {{ ($active ?? '') == 'guests' ? 'active' : '' }}"
            href="{{ url('admin/guests') }}"><svg class="ic " width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="9" cy="8" r="3.5" />
                <path d="M2 20c0-4 3-6 7-6s7 2 7 6M16 4.5a3.5 3.5 0 010 7M18 14c2.5.6 4 2.5 4 6" />
            </svg><span>Guests</span></a></li>
@endcan
@can('view payments')
<li><a class="ad-nav__a {{ ($active ?? '') == 'payments' ? 'active' : '' }}"
        href="{{ url('admin/payments') }}"><svg class="ic " width="18" height="18"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="2" y="5" width="20" height="14" rx="2" />
            <path d="M2 10h20M6 15h4" />
        </svg><span>Payments &amp; Invoices</span></a></li>
@endcan
@can('manage housekeeping')
<li><a class="ad-nav__a {{ ($active ?? '') == 'housekeeping' ? 'active' : '' }}"
    href="{{ url('admin/housekeeping') }}"><svg class="ic " width="18" height="18"
        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M14 3l7 7M4 20l6-6M10 14l4-4 5 5-4 4a6 6 0 01-5 0z" />
    </svg><span>Housekeeping</span></a></li>
@endcan
@can('manage services')
<li><a class="ad-nav__a {{ ($active ?? '') == 'services' ? 'active' : '' }}"
href="{{ url('admin/services') }}"><svg class="ic " width="18" height="18"
    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path
        d="M12 20c-4 0-8-3-8-8 4 0 7 2 8 5 1-3 4-5 8-5 0 5-4 8-8 8zM12 17c-2-3-2-7 0-11 2 4 2 8 0 11z" />
</svg><span>Spa &amp; Dining</span></a></li>
@endcan
</ul>
<p class="ad-nav__g">Content</p>
<ul>
@can('manage reviews')
<li><a class="ad-nav__a {{ ($active ?? '') == 'reviews' ? 'active' : '' }}"
href="{{ url('admin/reviews') }}"><svg class="ic " width="18" height="18"
    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z" />
</svg><span>Reviews</span></a></li>
@endcan
@can('manage blog')
<li><a class="ad-nav__a {{ ($active ?? '') == 'blog' ? 'active' : '' }}"
href="{{ url('admin/blog') }}"><svg class="ic " width="18" height="18"
viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M6 3h8l4 4v14H6zM14 3v4h4M9 12h6M9 16h6" />
</svg><span>Blog Posts</span></a></li>
@endcan
</ul>
<p class="ad-nav__g">Insights &amp; System</p>
<ul>
@can('view reports')
<li><a class="ad-nav__a {{ ($active ?? '') == 'reports' ? 'active' : '' }}"
href="{{ url('admin/reports') }}"><svg class="ic " width="18" height="18"
viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<path d="M4 20V4M4 20h16M8 16v-5M12 16V8M16 16v-3" />
</svg><span>Reports</span></a></li>
@endcan
@can('manage settings')
<li><a class="ad-nav__a {{ ($active ?? '') == 'settings' ? 'active' : '' }}"
href="{{ url('admin/settings') }}"><svg class="ic " width="18" height="18"
viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<circle cx="12" cy="12" r="3" />
<path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2" />
</svg><span>Settings</span></a></li>
@endcan
</ul>
@canany(['manage users', 'view roles', 'manage roles', 'view permissions', 'manage permissions'])
    <p class="ad-nav__g">Access control</p>
    <ul>
        @can('manage users')
            <li>
                <a class="ad-nav__a {{ ($active ?? '') == 'users' ? 'active' : '' }}" href="{{ route('admin.access.users.index') }}">
                    <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="8" r="3.5" />
                        <path d="M2 20c0-4 3-6 7-6s7 2 7 6M16 5a3 3 0 010 6M18 14c2.5.6 4 2.5 4 6" />
                    </svg>
                    <span>User access</span>
                </a>
            </li>
        @endcan
        @canany(['view roles', 'manage roles'])
            <li>
                <a class="ad-nav__a {{ ($active ?? '') == 'roles' ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                    <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="8" cy="8" r="4" />
                        <path d="M2 20c0-4 2-6 6-6 2 0 3.5.7 4.5 2M15 14l2 2 4-5" />
                    </svg>
                    <span>Roles</span>
                </a>
            </li>
        @endcanany
        @canany(['view permissions', 'manage permissions'])
            <li>
                <a class="ad-nav__a {{ ($active ?? '') == 'permissions' ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                    <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M7 10h10M7 14h6" />
                    </svg>
                    <span>Permissions</span>
                </a>
            </li>
        @endcanany
    </ul>
@endcanany
</nav>
<div class="ad-side__foot"><a href="{{ url('/') }}" target="_blank" rel="noopener"><svg class="ic "
width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<circle cx="12" cy="12" r="9" />
<path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18" />
</svg><span>View website</span></a></div>
</aside>
