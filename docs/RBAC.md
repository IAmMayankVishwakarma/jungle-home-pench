# Role-based access control

The application uses many-to-many role and permission assignments. Run the migrations and seed the starter roles and permissions with:

```sh
php artisan migrate --seed
```

`RbacSeeder` assigns the `super-admin` role to the account configured by `RBAC_INITIAL_ADMIN_EMAIL`. The default matches the initial account created by `DatabaseSeeder` (`test@example.com`). Set the variable to an existing administrator's email before seeding an existing installation; seeding fails explicitly if that account cannot be found.

The seeded roles are `super-admin`, `admin`, `editor`, and `viewer`. The super-admin role bypasses permission checks and is protected from editing or deletion in the admin UI. Role permissions and user assignments are managed at `/admin/roles`, `/admin/permissions`, and `/admin/access/users`.

Use permission slugs such as `users.view` or `roles.manage` with the `permission` route middleware. Laravel Gates and Blade `@can` checks also resolve the admin theme's existing abilities, such as `manage rooms`, to the corresponding module permission.
