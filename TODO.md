# TODO: Fix Stock Movement Tests

## Tasks
- [x] Update migration `database/migrations/2025_10_03_223930_create_stock_movements_table.php` to include missing columns: product_id, type, quantity, reference_type, notes, user_id.
- [x] Change test user role in `tests/Feature/StockTest.php` from 'estoquista' to 'admin'.
- [x] Run migrations to apply changes.
- [x] Run PHPUnit tests for StockTest to verify fixes.
