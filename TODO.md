# TODO: Reactivate Admin/Superadmin Users and Fix Access Issues

## Tasks
- [x] Update AdminUserController to include 'active' field in edit/update forms and logic
- [x] Create Artisan command to bulk reactivate admin and superadmin users
- [x] Update user views to show and allow toggling active status
- [x] Run migrations to ensure database schema is up to date
- [x] Run seeders to create default admin and superadmin users
- [x] Run command to reactivate admin/superadmin users (0 users reactivated, as seeders create them active)
- [x] Verify middleware and routes for proper access levels (middleware checks active and role, routes use role middleware correctly)
- [ ] Test login and access for admin/superadmin users
