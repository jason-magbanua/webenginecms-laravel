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

### Phase 1 — Foundation ✅

Core Laravel setup, database configuration, authentication, models, routing, and middleware.

**Database**
- [x] `config/database.php` — two `sqlsrv` connections only (`memuonline`, `muonline`); no sqlite/mysql/pgsql
- [x] `config/webengine.php` — server settings, feature flags, rankings config, brute force config
- [x] `.env.example` — `DB_DRIVER`, `DB_ACCOUNT_*`, `DB_GAME_*`, `ADMIN_USERNAMES`, `SERVER_FILES`, `SERVER_NAME`, `SERVER_SEASON`
- [x] `database/migrations/` — single migration creates all 19 `WEBENGINE_*` tables

**Authentication**
- [x] `app/Auth/MuOnlineUserProvider.php` — custom `UserProvider`; plain text `validateCredentials`
- [x] `app/Providers/AppServiceProvider.php` — registers the `muonline` auth provider + `Settings` singleton
- [x] `config/auth.php` — `accounts` guard using `muonline` provider

**Middleware**
- [x] `CheckAdmin` — validates `config('webengine.admin_usernames')`
- [x] `CheckBanned` — checks `WEBENGINE_BANS` active scope
- [x] `CheckIpBlocked` — checks `WEBENGINE_BLOCKED_IP`; applied globally
- [x] Aliases registered: `admin`, `check.banned`, `check.ip`

**Models**
- [x] `Account\Account` — `MEMB_INFO`, implements `Authenticatable`, PK `memb_guid`, identifier `memb___id`
- [x] `Account\AccountCharacter` — `AccountCharacter`
- [x] `Account\MemberStatus` — `MEMB_STAT`
- [x] `Game\Character` — `Character`, PK `Name` (string), relations to `GuildMember` / `Guild`
- [x] `Game\Guild` — `Guild`, PK `G_Name` (string)
- [x] `Game\GuildMember` — `GuildMember`
- [x] `Game\CastleSiege` / `CastleSiegeRegistration` / `CastleSiegeGuild`
- [x] `WebEngine\News`, `Ban` (with `scopeActive`), `BanLog`, `BlockedIp`
- [x] `WebEngine\Credit`, `CreditConfig`, `CreditLog`
- [x] `WebEngine\CronJob`, `Download`, `FailedLoginAttempt`
- [x] `WebEngine\PassChangeRequest`, `PaypalTransaction`, `Plugin`
- [x] `WebEngine\RegisterAccount`, `Vote`, `VoteLog`, `VoteSite`, `AccountCountry`
- [x] `WebEngine\Setting` — DB-backed runtime settings

**Routes**
- [x] `routes/web.php` — public + auth (guest) + UserCP (auth + check.banned)
- [x] `routes/admin.php` — admin/* (auth + admin middleware)
- [x] `routes/api.php` — guild-mark, paypal/ipn, cron trigger, castle-siege

**Support**
- [x] `app/Support/MuHelper.php` — `className()`, `classCss()`, `mapName()`, `pkLevel()`, `guildMarkUrl()`; constants for all character classes, maps, PK levels
- [x] `app/Support/Settings.php` — DB-backed settings facade helper with cache layer

---

### Phase 2 — Public Frontend ✅

All public-facing controllers and Blade views.

**Controllers** (`app/Http/Controllers/Public/`)
- [x] `HomeController` — latest 7 news + top 10 level/guild rankings with 5-min cache
- [x] `NewsController` — paginated news list + individual news show (increments `news_views`)
- [x] `RankingsController` — 9 ranking types (level, resets, grand resets, killers, guilds, online, votes, gens, master) with `Cache::remember(300)` and DB fallback
- [x] `ProfileController` — player profile + guild profile with online status
- [x] `CastleSiegeController` — current siege holder + registered guilds
- [x] `ContactController` — contact form with `Mail::raw()` delivery
- [x] `DownloadsController` — downloads grouped by type (clients / patches / tools)
- [x] `DonationController` — donation info view with PayPal link generation
- [x] `VoteController` — vote site listing + authenticated daily vote claim

**Views** (`resources/views/public/`)
- [x] `home.blade.php`
- [x] `news/index.blade.php`, `news/show.blade.php`
- [x] `rankings/_nav.blade.php` (shared nav), `rankings/level.blade.php`, `resets.blade.php`, `grandresets.blade.php`, `killers.blade.php`, `guilds.blade.php`, `online.blade.php`, `votes.blade.php`, `gens.blade.php`, `master.blade.php`
- [x] `profile/player.blade.php`, `profile/guild.blade.php`
- [x] `castle-siege.blade.php`, `contact.blade.php`, `downloads.blade.php`, `donation.blade.php`, `vote.blade.php`
- [x] `layouts/app.blade.php` — Bootstrap 3.4.1, top auth bar, Rankings dropdown, flash messages

---

### Phase 3 — User Control Panel ✅

Authentication flows and all UserCP features.

**Auth Controllers** (`app/Http/Controllers/Auth/`)
- [x] `LoginController` — show form, login (with brute force tracking via `WEBENGINE_FLA`), logout
- [x] `RegisterController` — show form, register (username/password/email validation, duplicate checks, inserts into `MEMB_INFO`)
- [x] `PasswordController` — forgot password form, send reset email via `PassChangeRequest` token, reset password

**UserCP Controllers** (`app/Http/Controllers/UserCP/`)
- [x] `DashboardController` — account overview, character list with online status, dynamic credits from all configured `WEBENGINE_CREDITS_CONFIG` entries
- [x] `AccountController` — `myAccount`, `myPassword` (current + new + confirm), `myEmail` (with duplicate check)
- [x] `CharacterController` — reset, add stats (STR/AGI/VIT/ENE/CMD), clear PK, unstick (moves to Lorencia), clear master skill tree; all with ownership validation

**Views** (`resources/views/`)
- [x] `auth/login.blade.php`, `auth/register.blade.php`
- [x] `usercp/dashboard.blade.php` — account info table, credits rows, character grid with online indicators
- [x] `usercp/myaccount.blade.php`, `usercp/mypassword.blade.php`, `usercp/myemail.blade.php`
- [x] `usercp/reset.blade.php`, `usercp/addstats.blade.php`, `usercp/clearpk.blade.php`, `usercp/unstick.blade.php`, `usercp/clearskilltree.blade.php`
- [x] `usercp/vote.blade.php`

---

### Phase 4 — Admin Panel ✅

Full admin panel with sidebar layout, 37 routes, 10 controllers, 20 views.

**Controllers** (`app/Http/Controllers/Admin/`)
- [x] `DashboardController` — system stats (total accounts, characters, news, active bans, online count), PHP/Laravel/OS info
- [x] `NewsController` — full CRUD (list, create, edit, delete)
- [x] `AccountController` — search accounts, view account detail (info, characters, bans, edit actions), online accounts list, new registrations, accounts by IP
- [x] `CharacterController` — search characters, edit all character stats (class, level, stats, zen, PK, resets, master level)
- [x] `BanController` — list bans, create ban (temporary or permanent, sets `bloc_code`), lift ban, blocked IP management (add/remove)
- [x] `CreditController` — add/remove credits via dynamic config, PayPal transaction log, top voters leaderboard
- [x] `CacheController` — view and clear all known Laravel cache keys (rankings + online_characters)
- [x] `CronController` — list cron jobs, toggle enable/disable, trigger individual jobs via `Artisan::call`
- [x] `PluginController` — list installed plugins, toggle enable/disable
- [x] `SettingsController` — DB-persisted runtime settings (server name, season, files, feature flags, brute force config)

**Views** (`resources/views/admin/`)
- [x] `layouts/admin.blade.php` — Bootstrap 3.4.1 sidebar layout with full nav and logout
- [x] `dashboard.blade.php`
- [x] `news/` — `index.blade.php`, `create.blade.php`, `edit.blade.php`
- [x] `accounts/` — `index.blade.php`, `show.blade.php`, `online.blade.php`, `registrations.blade.php`, `by-ip.blade.php`
- [x] `characters/` — `index.blade.php`, `edit.blade.php`
- [x] `bans/` — `index.blade.php`, `create.blade.php`, `blocked-ips.blade.php`
- [x] `credits/` — `index.blade.php`, `paypal.blade.php`, `top-voters.blade.php`
- [x] `cache/index.blade.php`
- [x] `cron/index.blade.php`
- [x] `plugins/index.blade.php`
- [x] `settings/index.blade.php`

---

### Phase 5 — Background Jobs & Scheduler ✅

All 15 cron jobs implemented as a single dispatching pair: `cron:dispatch` checks intervals and fires `cron:run {job}` for each due job. Seeder populates `WEBENGINE_CRON` with default entries.

**Commands** (`app/Console/Commands/`)
- [x] `cron:dispatch` — queries enabled jobs, checks `cron_last_run` + `cron_run_time` interval, dispatches due jobs
- [x] `cron:run {job}` — executes a named job; supports all 15 job types via match expression:
  - [x] `online_characters` — caches connected character names from `MEMB_STAT` + `AccountCharacter`
  - [x] `temporal_bans` — lifts expired bans, unsets `bloc_code`, logs to `WEBENGINE_BAN_LOG`
  - [x] `levels_ranking` — pre-warms `rankings_level` cache
  - [x] `resets_ranking` — pre-warms `rankings_resets` cache
  - [x] `grandresets_ranking` — pre-warms `rankings_grandresets` cache
  - [x] `killers_ranking` — pre-warms `rankings_killers` cache
  - [x] `guilds_ranking` — pre-warms `rankings_guilds` cache
  - [x] `votes_ranking` — pre-warms `rankings_votes` cache
  - [x] `gens_ranking` — pre-warms `rankings_gens` cache
  - [x] `masterlevel_ranking` — pre-warms `rankings_master` cache
  - [x] `online_ranking` — pre-warms `rankings_online` cache
  - [x] `castle_siege` — syncs castle siege holder from `MuCastle_SIEGE_GUILDLIST`
  - [x] `account_country` — IP geolocation for accounts → `WEBENGINE_ACCOUNT_COUNTRY`
  - [x] `character_country` — IP geolocation for characters → `WEBENGINE_CHARACTER_COUNTRY`
  - [x] `server_info` — caches server stats snapshot for admin dashboard

**Scheduler**
- [x] `routes/console.php` — `cron:dispatch` registered on a per-minute schedule
- [x] `database/seeders/CronJobSeeder.php` — seeds all 15 jobs with default intervals

---

### Phase 6 — API & Integrations ✅

**Controllers** (`app/Http/Controllers/Api/`)
- [x] `GuildMarkController` — `GET /api/guild-mark/{guild}` — serves binary guild emblem image
- [x] `PaypalController` — `POST /api/paypal/ipn` — verifies IPN, credits account on `Completed`, logs to `WEBENGINE_PAYPAL_TRANSACTIONS`
- [x] `CronTriggerController` — `POST /api/cron/trigger` — `CRON_TOKEN`-authenticated HTTP trigger for external cron services
- [x] `CastleSiegeController` — `GET /api/castle-siege` — returns current siege state as JSON

**Integrations**
- [x] PayPal IPN verification against `https://ipnpb.paypal.com/cgi-bin/webscr`
- [x] IP geolocation via external HTTP service (used by `account_country` / `character_country` jobs)
- [ ] Vote site callback / polling — `VoteSite` model and vote claim exist; external API polling not yet implemented

---

### Pending / Known Gaps

- [ ] **Vote site polling** — automatic credit award via external vote-site APIs (GT/TopG/etc.) not yet implemented; currently manual claim only
- [ ] **Email verification** — feature flag exists (`webengine.features.email_verification`) but the verification flow is not wired up
- [ ] **Admin: Downloads management** — public listing works; no admin CRUD to add/edit/remove download entries
- [ ] **Plugin system** — `PluginController` can toggle plugins; no actual plugin loader or hook system implemented
- [ ] **Test suite** — no feature or unit tests written yet

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
