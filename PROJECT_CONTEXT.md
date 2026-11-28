# Project context

Short, maintained map for future design and feature work. Verify referenced code when a request depends on details that may have changed.

## Application

- Laravel 13 / PHP 8.3, Blade views, Laravel Fortify, Socialite, and Eloquent.
- `routes/web.php` defines the public pages, Google OAuth, verified dashboard, and admin RBAC routes. Fortify registers the authentication routes.
- The main site layout is `resources/views/layouts/app.blade.php`. It loads Bootstrap 5.3.3 and `public/assets/css/style.css` / `public/assets/js/main.js` directly.
- Admin layouts and styles are under `resources/views/admin/` and `public/assets/css/admin.css`.
- `resources/css/app.css` and `resources/js/app.js` exist for the Vite pipeline; check actual layout usage before choosing them for a change.

## Design system

The current public site is styled as a boutique luxury resort:

- Colors from `public/assets/css/style.css`: gold `#b08d57`, deep charcoal `#1b1b1b`, ink `#1d1b1a`, warm light background `#f5efee`, white surfaces.
- Typography: Bodoni Moda for headings and Outfit for body/UI text; loaded by `resources/views/layouts/app.blade.php`.
- Visual language: spacious editorial layouts, large photographic hero sections with dark overlays, restrained gold accents, fine borders, and uppercase, letter-spaced calls to action.
- Reuse existing patterns such as `hero`, `sec`, `btn-pt`, and the `pt-` component classes for the public site. Admin components use `ad-` / `btn-ad`; auth-card components use `au-`.
- The repository is named Jungle Home Pench, but current page copy and styles identify the site as Patara Luxury Resort & Spa Hotel. Preserve the existing visual system; do not silently invent a jungle-green theme or rebrand. Ask/confirm if a request requires resolving the brand mismatch.

## Existing functionality

| Area | Current behavior | Main implementation |
| --- | --- | --- |
| Public resort pages | Home, about, rooms, room details, services, restaurant, gallery, FAQ, cart, coming soon, blog, and contact render Blade pages. | `routes/web.php`, `resources/views/pages/` |
| Booking UI | Date/guest booking forms and room cards appear in the public templates; no booking submission route is currently registered. Treat them as presentation until a backend flow is implemented. | `resources/views/pages/index.blade.php`, `resources/views/partials/booking.blade.php` |
| Authentication | Fortify-backed login, registration, password reset, email verification, profile/password actions, two-factor and passkey support are configured. Login validates credentials before a 2FA challenge; 2FA enrollment requires recent password confirmation and is covered end-to-end; signed verification links can verify without an active session and then return to login. Verification, password confirmation, 2FA settings, and challenge screens use the resort auth styling. | `app/Providers/FortifyServiceProvider.php`, `app/Http/Controllers/Auth/VerifyEmailLinkController.php`, `app/Actions/Fortify/`, `config/fortify.php`, `routes/web.php`, `resources/views/Auth/`, `resources/views/admin/pages/login.blade.php`, `tests/Feature/Auth/` |
| Google sign-in/linking | OAuth sign-in and linking are handled by a custom Socialite controller; linking is available to authenticated, verified users. | `app/Http/Controllers/Auth/GoogleController.php`, `routes/web.php` |
| Roles and permissions | Users have many-to-many roles and permissions; admin routes support role/permission management and assigning roles to users. | `app/Models/User.php`, `app/Models/Role.php`, `app/Models/Permission.php`, `app/Http/Controllers/Admin/`, `docs/RBAC.md` |
| Admin screens | Dashboard, bookings, calendar, guests, housekeeping, payments, reports, reviews, rooms, services, and settings Blade screens exist. Do not assume each screen has working routes or persistence; confirm route/controller/model support before extending one as functionality. | `resources/views/admin/`, `routes/web.php` |

## Working conventions

- Prefer the existing Blade layout, CSS variables, component classes, and route names over parallel implementations.
- Keep project notes factual and brief. When a feature is added or changed, record its user-visible behavior, key files/routes/data changes, and focused verification here. Mark UI-only or unfinished work explicitly.
- Check the worktree before editing; preserve unrelated in-progress changes.
- Targeted verification: `php artisan route:list` for routing and `php artisan test` for the relevant tests.
