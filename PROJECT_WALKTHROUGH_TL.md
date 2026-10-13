# Project Walkthrough: Campus Event Management

> Gabay ito para maipaliwanag ang existing project sa presentation. Ang code snippets ay halimbawa ng current implementation; huwag patakbuhin ang `make:model` commands ulit dahil nagagawa na ang mga file.

## Ano ang app?

Laravel application ito para pamahalaan ang campus events. Ang limang project tables ay `organizers`, `venues`, `events`, `participants`, at `registrations`. May standard Laravel support tables din para sa users, sessions, cache, at queue; hindi sila dagdag na project modules.

## MySQL at phpMyAdmin

Hindi direktang kumokonekta ang Laravel sa phpMyAdmin. Kumokonekta ito sa MySQL server. Ang phpMyAdmin ay web interface lamang para gumawa, tumingin, at mag-manage ng MySQL databases at tables.

1. Buksan ang XAMPP Control Panel at i-start ang **MySQL**. I-start din ang Apache kung phpMyAdmin sa browser ang gagamitin.
2. Buksan ang `http://localhost/phpmyadmin`.
3. Gumawa ng database na `student_management_system` (o piliin ang existing database na ganoon ang pangalan).
4. Sa `.env`, ilagay ang local MySQL settings. Huwag ilagay o i-upload ang totoong password sa `PROJECT_WALKTHROUGH_TL.md` o sa GitHub.

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Kung nagtakda ka ng MySQL password, ilagay iyon sa local `.env` lamang. Panatilihing generic ang credentials sa `.env.example`.

## Setup Commands

> Patakbuhin ang commands mula sa project root folder na may `artisan` file.

```bash
composer install
php artisan config:clear
php artisan migrate --seed
php artisan serve
```

- `composer install` installs PHP dependencies.
- `config:clear` pinapabasa muli kay Laravel ang `.env` settings.
- `migrate` creates tables from migration files. `--seed` runs `DatabaseSeeder` pagkatapos.
- `serve` starts the web app, normally at `http://127.0.0.1:8000`.

> Kung na-run na ang `migrate --seed` sa database mo, huwag ulitin ang `db:seed` nang walang dahilan dahil magdadagdag ito ng panibagong demo records. Huwag gamitin ang `migrate:fresh` sa database na may datos: binubura nito ang tables bago buuing muli.

Para makita ang project tables, buksan ang `http://localhost/phpmyadmin`, piliin ang `student_management_system`, at tingnan ang `events`, `organizers`, `venues`, `participants`, at `registrations`.

## Paano Ginagawa ang Tables

> Ang `make:model` commands ay scaffolding commands para sa bagong project. Nagawa na ang mga model, factory, at migration file rito. Sa existing project, `php artisan migrate` lang ang kailangan para ilapat ang pending migration.

Halimbawa ng command para gumawa ng model, migration, at factory:

```bash
php artisan make:model Organizer -mf
php artisan make:model Venue -mf
php artisan make:model Event -mf
php artisan make:model Participant -mf
php artisan make:model Registration -mf
```

> Huwag patakbuhin ang mga ito sa existing workspace; may kaparehong files na at maaaring magkaroon ng duplicate classes o migrations.

Sa bawat migration, inilalarawan ng `Schema::create()` ang table. Halimbawa, ganito ang mahalagang bahagi ng `database/migrations/2026_10_13_000003_create_events_table.php`:

```php
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->foreignId('organizer_id')->constrained()->restrictOnDelete();
    $table->foreignId('venue_id')->constrained()->restrictOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->dateTime('starts_at');
    $table->dateTime('ends_at');
    $table->unsignedInteger('capacity');
    $table->string('status')->default('scheduled');
    $table->timestamps();
});
```

`up()` ang gumagawa ng table; `down()` ang nagtatanggal nito kapag ni-ro-roll back ang migration. Ang `foreignId(...)->constrained()` ay foreign key papunta sa related table. Ang `restrictOnDelete()` pumipigil magtanggal ng organizer o venue na ginagamit pa ng event.

### Limang project migrations

| Migration file | Nilalaman |
| --- | --- |
| `2026_10_13_000001_create_organizers_table.php` | Pangalan, email, phone, department, bio |
| `2026_10_13_000002_create_venues_table.php` | Pangalan, location, capacity, description |
| `2026_10_13_000003_create_events_table.php` | Organizer at venue foreign keys, title, schedule, capacity, status |
| `2026_10_13_000004_create_participants_table.php` | Pangalan, student ID, email, phone, course, year level |
| `2026_10_13_000005_create_registrations_table.php` | Event at participant foreign keys, registration date, status, notes |

May unique constraint ang registration pair na `(event_id, participant_id)`, kaya hindi makakapag-register nang dalawang beses ang parehong participant sa iisang event. Kapag binura ang event o participant, awtomatikong mabubura ang kaugnay nitong registration (`cascadeOnDelete`).

## File Map

### Models: `app/Models/`

- `Organizer.php`: organizer data at `events()` relationship (`hasMany`).
- `Venue.php`: venue data at `events()` relationship (`hasMany`).
- `Event.php`: event data; `belongsTo` organizer at venue, at `hasMany` registrations.
- `Participant.php`: student data at `hasMany` registrations.
- `Registration.php`: nag-uugnay ng isang event at participant; parehong `belongsTo`.

Ang `$fillable` sa bawat model ang listahan ng fields na pinapayagang i-save mula sa validated form data.

### Controllers: `app/Http/Controllers/`

- `DashboardController.php`: kumukuha ng totals, upcoming events, at latest registrations.
- `ResourceController.php`: shared CRUD methods (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) para maiwasan ang paulit-ulit na code.
- `EventsController.php`, `OrganizersController.php`, `VenuesController.php`, `ParticipantsController.php`, `RegistrationsController.php`: bawat isa ang nagtatakda ng model, form fields, list columns, relationships, at validation rules.

Sa `store()` at `update()`, tinatawag ang `$request->validate(...)` bago mag-save. Kapag invalid ang input, ibinabalik ni Laravel ang form kasama ang validation errors at old values.

### Routes: `routes/web.php`

Ang `Route::get('/', DashboardController::class)` ang dashboard route. Ang `Route::resource('events', EventsController::class)` ay gumagawa ng standard CRUD routes gaya ng `events.index`, `events.create`, `events.store`, `events.show`, `events.edit`, `events.update`, at `events.destroy`. Ganoon din ang apat na ibang resources.

Tingnan ang lahat ng registered routes:

```bash
php artisan route:list
```

### Views: `resources/views/`

- `layouts/app.blade.php`: shared navigation, success/error alerts, Bootstrap CDN, at styling.
- `dashboard.blade.php`: dashboard tables at totals.
- `records/index.blade.php`: reusable listing table at View/Edit/Delete actions.
- `records/form.blade.php`: reusable create/edit form.
- `records/show.blade.php`: reusable record details page.

Ang forms ay gumagamit ng CSRF token (`@csrf`) at HTTP method spoofing (`@method('PUT')` / `@method('DELETE')`) para sa secure Laravel form submissions.

### Factories at Seeder

Ang limang `database/factories/*Factory.php` files ay gumagawa ng sample organizer, venue, event, participant, at registration. Ang `database/seeders/DatabaseSeeder.php` ay gumagawa ng 5 organizers, 5 venues, 10 events, 40 participants, at 80 registrations na may valid relationships.

Tingnan ang migration status at patakbuhin ang tests:

```bash
php artisan migrate:status
php artisan test
```

## Paano Dumadaloy ang Isang CRUD Request

Halimbawa ng pag-save ng event:

1. Pinipili ng browser ang `POST /events` mula sa form sa `records/form.blade.php`.
2. Ang resource route sa `routes/web.php` ang nagtatawag sa `EventsController` na nagmamana sa `ResourceController`.
3. Vine-validate ng controller ang title, organizer, venue, schedule, capacity, at status.
4. Ginagamit ng Eloquent `Event` model ang `events` table na nilikha ng migration.
5. Pagkatapos ma-save, bumabalik sa event list na may success message.

## Maikling Presentation Explanation

> “Laravel ang framework ng system. Ang routes ang nagdidirekta ng request sa controller. Nagva-validate ang controller at gumagamit ng Eloquent model para bumasa o magsulat sa MySQL. Ang migration ang nagtatakda ng schema at relationships; ang Blade views naman ang nagpapakita ng forms at records. Sa phpMyAdmin ko tinitingnan ang MySQL database, pero sa MySQL server mismo kumokonekta ang Laravel.”