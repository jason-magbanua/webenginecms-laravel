# WebEngine CMS — Laravel Rewrite

A full rewrite of [WebEngineCMS](https://webenginecms.org/) from a legacy procedural PHP codebase into a modern **Laravel 13** application.

## Stack

| Component | Version |
|---|---|
| PHP | 8.5.4 |
| Laravel | 13.x |
| Database | SQL Server (dual DB via `sqlsrv`/`dblib`) |
| Frontend | Bootstrap 3.4.1 |
| Composer | 2.9.5 |

## Architecture Decisions

- **Two database connections only:** `memuonline` (Me_MuOnline — accounts) and `muonline` (MuOnline — characters + all WEBENGINE_* tables)
- **Plain text passwords** — matches the original game server convention; no bcrypt or legacy hash support
- **English only** — multi-language support removed entirely
- **Custom Auth Guard** — `MuOnlineUserProvider` with `memb___id` as the auth identifier
- **Bootstrap 3.4.1** — matches the original CMS frontend

---

## Rewrite Progress

### Phase 1 — Foundation `COMPLETE`

Core Laravel setup, database configuration, authentication, models, routing, and middleware.

**Database**
- `config/database.php` — two `sqlsrv` connections only (`memuonline`, `muonline`); no sqlite/mysql/pgsql
- `config/webengine.php` — server settings, feature flags, rankings config, brute force config
- `.env.example` — `DB_DRIVER`, `DB_ACCOUNT_*`, `DB_GAME_*`, `ADMIN_USERNAMES`, `SERVER_FILES`, `SERVER_NAME`, `SERVER_SEASON`

**Authentication**
- `app/Auth/MuOnlineUserProvider.php` — custom `UserProvider`; plain text `validateCredentials`
- `app/Providers/AppServiceProvider.php` — registers the `muonline` auth provider
- `config/auth.php` — `accounts` guard using `muonline` provider

**Middleware**
- `CheckAdmin` — validates `config('webengine.admin_usernames')`
- `CheckBanned` — checks `WEBENGINE_BANS` active scope
- `CheckIpBlocked` — checks `WEBENGINE_BLOCKED_IP`; applied globally
- Aliases registered: `admin`, `check.banned`, `check.ip`

**Models**
- `Account\Account` — `MEMB_INFO`, implements `Authenticatable`, PK `memb_guid`, identifier `memb___id`
- `Account\AccountCharacter` — `AccountCharacter`
- `Account\MemberStatus` — `MEMB_STAT`
- `Game\Character` — `Character`, PK `Name` (string), relations to `GuildMember` / `Guild`
- `Game\Guild` — `Guild`, PK `G_Name` (string)
- `Game\GuildMember` — `GuildMember`
- `Game\CastleSiege` / `CastleSiegeRegistration` / `CastleSiegeGuild`
- `WebEngine\News`, `Ban` (with `scopeActive`), `BanLog`, `BlockedIp`
- `WebEngine\Credit`, `CreditConfig`, `CreditLog`
- `WebEngine\CronJob`, `Download`, `FailedLoginAttempt`
- `WebEngine\PassChangeRequest`, `PaypalTransaction`, `Plugin`
- `WebEngine\RegisterAccount`, `Vote`, `VoteLog`, `VoteSite`, `AccountCountry`

**Routes**
- `routes/web.php` — public + auth (guest) + UserCP (auth + check.banned)
- `routes/admin.php` — admin/* (auth + admin middleware)
- `routes/api.php` — guild-mark, paypal/ipn, cron trigger, castle-siege

**Support**
- `app/Support/MuHelper.php` — `className()`, `classCss()`, `mapName()`, `pkLevel()`, `guildMarkUrl()`; constants for all character classes, maps, PK levels

---

### Phase 2 — Public Frontend `COMPLETE`

All public-facing controllers and Blade views.

**Controllers** (`app/Http/Controllers/Public/`)
- `HomeController` — latest 7 news + top 10 level/guild rankings with 5-min cache
- `NewsController` — paginated news list + individual news show (increments `news_views`)
- `RankingsController` — 9 ranking types (level, resets, grand resets, killers, guilds, online, votes, gens, master) with `Cache::remember(300)` and DB fallback
- `ProfileController` — player profile + guild profile with online status
- `CastleSiegeController` — current siege holder + registered guilds
- `ContactController` — contact form with `Mail::raw()` delivery
- `DownloadsController` — downloads grouped by type (clients / patches / tools)
- `DonationController` — static donation info view
- `VoteController` — vote site listing + authenticated daily vote claim

**Views** (`resources/views/public/`)
- `home.blade.php`
- `news/index.blade.php`, `news/show.blade.php`
- `rankings/_nav.blade.php` (shared nav), `rankings/level.blade.php`, `resets.blade.php`, `grandresets.blade.php`, `killers.blade.php`, `guilds.blade.php`, `online.blade.php`, `votes.blade.php`, `gens.blade.php`, `master.blade.php`
- `profile/player.blade.php`, `profile/guild.blade.php`
- `castle-siege.blade.php`, `contact.blade.php`, `downloads.blade.php`, `donation.blade.php`, `vote.blade.php`
- `layouts/app.blade.php` — Bootstrap 3.4.1, top auth bar, Rankings dropdown, flash messages

---

### Phase 3 — User Control Panel `COMPLETE`

Authentication flows and all UserCP features.

**Auth Controllers** (`app/Http/Controllers/Auth/`)
- `LoginController` — show form, login (with brute force tracking via `WEBENGINE_FLA`), logout
- `RegisterController` — show form, register (username/password/email validation, duplicate checks, inserts into `MEMB_INFO`)
- `PasswordController` — forgot password form, send reset email via `PassChangeRequest` token, reset password

**UserCP Controllers** (`app/Http/Controllers/UserCP/`)
- `DashboardController` — account overview, character list with online status, dynamic credits from all configured `WEBENGINE_CREDITS_CONFIG` entries
- `AccountController` — `myAccount`, `myPassword` (current + new + confirm), `myEmail` (with duplicate check)
- `CharacterController` — reset, add stats (STR/AGI/VIT/ENE/CMD), clear PK, unstick (moves to Lorencia), clear master skill tree; all with ownership validation

**Views** (`resources/views/`)
- `auth/login.blade.php`, `auth/register.blade.php`
- `usercp/dashboard.blade.php` — account info table, credits rows, character grid with online indicators
- `usercp/myaccount.blade.php`, `usercp/mypassword.blade.php`, `usercp/myemail.blade.php`
- `usercp/reset.blade.php`, `usercp/addstats.blade.php`, `usercp/clearpk.blade.php`, `usercp/unstick.blade.php`, `usercp/clearskilltree.blade.php`
- `usercp/vote.blade.php`

---

### Phase 4 — Admin Panel `COMPLETE`

Full admin panel with sidebar layout, 37 routes, 10 controllers, 20 views.

**Controllers** (`app/Http/Controllers/Admin/`)
- `DashboardController` — system stats (total accounts, characters, news, active bans, online count), PHP/Laravel/OS info
- `NewsController` — full CRUD (list, create, edit, delete)
- `AccountController` — search accounts, view account detail (info, characters, bans, edit actions), online accounts list, new registrations, accounts by IP
- `CharacterController` — search characters, edit all character stats (class, level, stats, zen, PK, resets, master level)
- `BanController` — list bans, create ban (temporary or permanent, sets `bloc_code`), lift ban, blocked IP management (add/remove)
- `CreditController` — add/remove credits via dynamic config, PayPal transaction log, top voters leaderboard
- `CacheController` — view and clear all known Laravel cache keys (rankings + online_characters)
- `CronController` — list cron jobs, toggle enable/disable, trigger individual jobs via `Artisan::call`
- `PluginController` — list installed plugins, toggle enable/disable
- `SettingsController` — runtime settings form (server name, season, files, features, brute force config)

**Views** (`resources/views/admin/`)
- `layouts/admin.blade.php` — Bootstrap 3.4.1 sidebar layout with full nav and logout
- `dashboard.blade.php`
- `news/` — `index.blade.php`, `create.blade.php`, `edit.blade.php`
- `accounts/` — `index.blade.php`, `show.blade.php`, `online.blade.php`, `registrations.blade.php`, `by-ip.blade.php`
- `characters/` — `index.blade.php`, `edit.blade.php`
- `bans/` — `index.blade.php`, `create.blade.php`, `blocked-ips.blade.php`
- `credits/` — `index.blade.php`, `paypal.blade.php`, `top-voters.blade.php`
- `cache/index.blade.php`
- `cron/index.blade.php`
- `plugins/index.blade.php`
- `settings/index.blade.php`

---

## Remaining Phases

### Phase 5 — Background Jobs & Scheduler `PENDING`

Migrate all 15 original WebEngine cron jobs into Laravel Artisan commands registered in the scheduler.

**Commands to implement** (`app/Console/Commands/`)

| Command | Description |
|---|---|
| `cron:account-country` | IP geolocation lookup for accounts — stores country code in `WEBENGINE_ACCOUNT_COUNTRY` for flag display |
| `cron:character-country` | IP geolocation lookup for characters — stores country code in `WEBENGINE_CHARACTER_COUNTRY` |
| `cron:temporal-bans` | Lift expired temporary bans — unsets `bloc_code`, deletes expired `WEBENGINE_BANS` records, logs to `WEBENGINE_BAN_LOG` |
| `cron:online-characters` | Queries the game server for currently connected character names, writes result to Laravel cache as `online_characters` |
| `cron:rankings-cache` | Pre-warms all 9 ranking cache keys (`rankings_level`, `rankings_resets`, etc.) from the DB |
| `cron:vote-check` | Polls vote site APIs to confirm pending votes and award credits |
| `cron:credit-log-cleanup` | Prunes old entries from `WEBENGINE_CREDITS_LOGS` beyond the configured retention period |
| `cron:failed-logins-cleanup` | Clears expired lockout records from `WEBENGINE_FLA` |
| `cron:paypal-verify` | Re-verifies pending PayPal IPN transactions against the PayPal API |
| `cron:register-cleanup` | Deletes expired unverified registration tokens from `WEBENGINE_REGISTER_ACCOUNT` |
| `cron:password-reset-cleanup` | Deletes expired password reset tokens from `WEBENGINE_PASSCHANGE_REQUEST` |
| `cron:guild-marks-cache` | Pre-generates/caches guild mark image paths |
| `cron:news-cache` | Pre-warms the news listing cache |
| `cron:castle-siege-update` | Syncs castle siege registration window state |
| `cron:stats-snapshot` | Takes a periodic snapshot of key server stats for the admin dashboard |

**Scheduler** (`routes/console.php`) — all jobs registered with their original intervals.

---

### Phase 6 — API & Integrations `PENDING`

Public and internal API endpoints, plus third-party service integrations.

**Controllers** (`app/Http/Controllers/Api/`)

| Controller | Route | Description |
|---|---|---|
| `GuildMarkController` | `GET /api/guild-mark/{guild}` | Serves the binary guild emblem image from the `MuCastle_SIEGE_GUILDLIST` or guild mark table |
| `PaypalController` | `POST /api/paypal/ipn` | Receives and verifies PayPal IPN callbacks; credits the account on `Completed` status; logs to `WEBENGINE_PAYPAL_TRANSACTIONS` |
| `CronTriggerController` | `POST /api/cron/trigger` | API-key-authenticated endpoint allowing external cron triggers (e.g. from a server-side crontab hitting a URL) to fire specific jobs |
| `CastleSiegeController` | `GET /api/castle-siege` | Returns current siege state and registered guilds as JSON (used by game server integrations) |

**Integrations**
- PayPal IPN verification against `https://ipnpb.paypal.com/cgi-bin/webscr`
- Vote site callback / polling per configured `WEBENGINE_VOTE_SITES`
- IP geolocation service integration for country flag lookups (used by Phase 5 cron jobs)

---

## Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### `.env` DB variables

```env
DB_DRIVER=sqlsrv

DB_ACCOUNT_HOST=127.0.0.1
DB_ACCOUNT_PORT=1433
DB_ACCOUNT_DATABASE=Me_MuOnline
DB_ACCOUNT_USERNAME=sa
DB_ACCOUNT_PASSWORD=

DB_GAME_HOST=127.0.0.1
DB_GAME_PORT=1433
DB_GAME_DATABASE=MuOnline
DB_GAME_USERNAME=sa
DB_GAME_PASSWORD=

SERVER_NAME="My MuOnline Server"
SERVER_SEASON=6
SERVER_FILES=igcn
ADMIN_USERNAMES=admin
```

## License

MIT
