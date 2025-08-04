<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Primary key for user record');
            $table->string('name')->comment('User full name. later, renamed to display_name');
            $table->string('email')->unique()->comment('User email address used for login');
            $table->string('password')->comment('Hashed user_password');
            $table->rememberToken()->comment('Remember me token for persistent login sessions');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary()->comment('Email address for the user requesting password reset');
            $table->string('token')->comment('Secure token for password reset verification');
            $table->timestamp('created_at')->nullable()->comment('Timestamp when the reset token was created');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary()->comment('Unique identifier for the session');
            $table->foreignId('user_id')->nullable()->index()->comment(
                'Foreign key to users table, null for guest sessions'
            );
            $table->string('ip_address', 45)->nullable()->comment('IP address of the user when session was created');
            $table->text('user_agent')->nullable()->comment('Browser/device information of the user');
            $table->longText('payload')->comment('Encrypted session data');
            $table->integer('last_activity')->index()->comment('Timestamp of the last activity in the session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
