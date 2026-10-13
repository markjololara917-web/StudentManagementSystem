## Campus Event Management System
A Laravel 12 application for organizing student events and managing participant registrations.

### Requirements

- PHP 8.2 or newer
- Composer
- SQLite (default) or another Laravel-supported database

### Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
```

For SQLite, create `database/database.sqlite` if it does not exist, then run:

```bash
php artisan migrate --seed
php artisan serve
```

Open `http://127.0.0.1:8000`. Bootstrap is loaded from its CDN.

### Features

- Dashboard with totals and upcoming event and registration activity
- Full CRUD for events, organizers, venues, participants, and registrations
- Validated schedules, unique student and email IDs, duplicate-registration prevention
- Eloquent relationships and foreign keys that protect related data
- Factories and sample data for all five project models

### Project tables

The five topic tables are `organizers`, `venues`, `events`, `participants`, and `registrations`. The Laravel starter retains its standard users, cache, and queue infrastructure migrations; these support Laravel and are not project modules.

### Tests

```bash
php artisan test
```

Tests use an in-memory SQLite database and do not alter the configured local database.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
