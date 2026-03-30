<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'muonline';

    public function up(): void
    {
        // News
        Schema::connection('muonline')->create('WEBENGINE_NEWS', function (Blueprint $table) {
            $table->increments('news_id');
            $table->string('news_title');
            $table->text('news_content');
            $table->string('news_author', 50);
            $table->dateTime('news_date');
            $table->string('news_category', 50)->nullable();
            $table->unsignedInteger('news_views')->default(0);
        });

        // Bans
        Schema::connection('muonline')->create('WEBENGINE_BANS', function (Blueprint $table) {
            $table->increments('ban_id');
            $table->string('ban_account', 50);
            $table->string('ban_reason', 200)->nullable();
            $table->string('ban_type', 20)->default('permanent'); // permanent | temporal
            $table->dateTime('ban_date');
            $table->dateTime('ban_expire')->nullable();
            $table->string('ban_by', 50);
        });

        // Ban Log
        Schema::connection('muonline')->create('WEBENGINE_BAN_LOG', function (Blueprint $table) {
            $table->increments('log_id');
            $table->string('log_account', 50);
            $table->string('log_action', 50);
            $table->dateTime('log_date');
            $table->string('log_by', 50);
        });

        // Blocked IPs
        Schema::connection('muonline')->create('WEBENGINE_BLOCKED_IP', function (Blueprint $table) {
            $table->increments('ip_id');
            $table->string('ip_address', 50)->unique();
            $table->string('ip_reason', 200)->nullable();
            $table->dateTime('ip_date');
        });

        // Credits (per-account credit balance)
        Schema::connection('muonline')->create('WEBENGINE_CREDITS', function (Blueprint $table) {
            $table->string('memb___id', 10)->primary();
            $table->integer('credits')->default(0);
            $table->integer('used')->default(0);
        });

        // Credit Configurations
        Schema::connection('muonline')->create('WEBENGINE_CREDITS_CONFIG', function (Blueprint $table) {
            $table->increments('config_id');
            $table->string('config_title', 50);
            $table->string('config_database', 50);
            $table->string('config_table', 50);
            $table->string('config_credits_col', 50);
            $table->string('config_user_col', 50);
            $table->string('config_user_col_id', 50);
            $table->tinyInteger('config_checkonline')->default(0);
            $table->tinyInteger('config_display')->default(1);
        });

        // Credit Logs
        Schema::connection('muonline')->create('WEBENGINE_CREDITS_LOGS', function (Blueprint $table) {
            $table->increments('log_id');
            $table->string('log_account', 50);
            $table->integer('log_credits');
            $table->string('log_type', 50);
            $table->dateTime('log_date');
            $table->string('log_description', 255)->nullable();
        });

        // Cron Jobs
        Schema::connection('muonline')->create('WEBENGINE_CRON', function (Blueprint $table) {
            $table->increments('cron_id');
            $table->string('cron_name', 100)->unique();
            $table->string('cron_command', 100);
            $table->unsignedInteger('cron_run_time')->default(300); // seconds
            $table->dateTime('cron_last_run')->nullable();
            $table->boolean('cron_status')->default(true);
        });

        // Downloads
        Schema::connection('muonline')->create('WEBENGINE_DOWNLOADS', function (Blueprint $table) {
            $table->increments('download_id');
            $table->string('download_name', 100);
            $table->string('download_description', 255)->nullable();
            $table->text('download_url');
            $table->decimal('download_size', 10, 2)->nullable();
            $table->dateTime('download_date');
            $table->string('download_version', 50)->nullable();
        });

        // Failed Login Attempts (brute force tracking)
        Schema::connection('muonline')->create('WEBENGINE_FLA', function (Blueprint $table) {
            $table->increments('fla_id');
            $table->string('fla_ip', 50)->index();
            $table->unsignedInteger('fla_attempts')->default(1);
            $table->dateTime('fla_last_attempt');
            $table->dateTime('fla_locked_until')->nullable();
        });

        // Password Change Requests
        Schema::connection('muonline')->create('WEBENGINE_PASSCHANGE_REQUEST', function (Blueprint $table) {
            $table->increments('request_id');
            $table->string('request_account', 50)->unique();
            $table->string('request_token', 100)->unique();
            $table->dateTime('request_date');
            $table->dateTime('request_expire');
        });

        // PayPal Transactions
        Schema::connection('muonline')->create('WEBENGINE_PAYPAL_TRANSACTIONS', function (Blueprint $table) {
            $table->increments('transaction_id');
            $table->string('transaction_account', 50)->index();
            $table->decimal('transaction_amount', 10, 2);
            $table->string('transaction_currency', 10)->default('USD');
            $table->string('transaction_status', 50);
            $table->string('transaction_paypal_id', 100)->unique();
            $table->dateTime('transaction_date');
            $table->integer('transaction_credits')->default(0);
        });

        // Plugins
        Schema::connection('muonline')->create('WEBENGINE_PLUGINS', function (Blueprint $table) {
            $table->increments('plugin_id');
            $table->string('plugin_name', 100);
            $table->string('plugin_slug', 100)->unique();
            $table->string('plugin_version', 20);
            $table->boolean('plugin_status')->default(false);
            $table->dateTime('plugin_installed_at');
        });

        // Pending Registrations (if email verification is enabled)
        Schema::connection('muonline')->create('WEBENGINE_REGISTER_ACCOUNT', function (Blueprint $table) {
            $table->increments('reg_id');
            $table->string('reg_account', 50)->unique();
            $table->string('reg_password', 100);
            $table->string('reg_email', 100);
            $table->string('reg_token', 100)->unique();
            $table->dateTime('reg_date');
            $table->dateTime('reg_expire');
        });

        // Votes
        Schema::connection('muonline')->create('WEBENGINE_VOTES', function (Blueprint $table) {
            $table->increments('vote_id');
            $table->string('vote_account', 50)->index();
            $table->unsignedInteger('vote_site_id');
            $table->dateTime('vote_date');
            $table->string('vote_ip', 50);
        });

        // Vote Logs (cooldown tracking per account + site)
        Schema::connection('muonline')->create('WEBENGINE_VOTE_LOGS', function (Blueprint $table) {
            $table->increments('log_id');
            $table->string('log_account', 50)->index();
            $table->unsignedInteger('log_site_id');
            $table->dateTime('log_date');
            $table->string('log_ip', 50);
            $table->integer('log_credits')->default(0);
        });

        // Vote Sites
        Schema::connection('muonline')->create('WEBENGINE_VOTE_SITES', function (Blueprint $table) {
            $table->increments('site_id');
            $table->string('site_name', 50);
            $table->text('site_url');
            $table->text('site_callback_url')->nullable();
            $table->integer('site_credits')->default(0);
            $table->boolean('site_status')->default(true);
        });

        // Account Country (geolocation cache)
        Schema::connection('muonline')->create('WEBENGINE_ACCOUNT_COUNTRY', function (Blueprint $table) {
            $table->string('memb___id', 10)->primary();
            $table->string('country_code', 10);
            $table->string('country_name', 100)->nullable();
            $table->string('last_ip', 50)->nullable();
            $table->dateTime('updated_at')->nullable();
        });

        // Settings (DB-persisted config overrides)
        Schema::connection('muonline')->create('WEBENGINE_SETTINGS', function (Blueprint $table) {
            $table->string('setting_key', 100)->primary();
            $table->text('setting_value')->nullable();
        });
    }

    public function down(): void
    {
        $tables = [
            'WEBENGINE_SETTINGS',
            'WEBENGINE_ACCOUNT_COUNTRY',
            'WEBENGINE_VOTE_SITES',
            'WEBENGINE_VOTE_LOGS',
            'WEBENGINE_VOTES',
            'WEBENGINE_REGISTER_ACCOUNT',
            'WEBENGINE_PLUGINS',
            'WEBENGINE_PAYPAL_TRANSACTIONS',
            'WEBENGINE_PASSCHANGE_REQUEST',
            'WEBENGINE_FLA',
            'WEBENGINE_DOWNLOADS',
            'WEBENGINE_CRON',
            'WEBENGINE_CREDITS_LOGS',
            'WEBENGINE_CREDITS_CONFIG',
            'WEBENGINE_CREDITS',
            'WEBENGINE_BLOCKED_IP',
            'WEBENGINE_BAN_LOG',
            'WEBENGINE_BANS',
            'WEBENGINE_NEWS',
        ];

        foreach ($tables as $table) {
            Schema::connection('muonline')->dropIfExists($table);
        }
    }
};
