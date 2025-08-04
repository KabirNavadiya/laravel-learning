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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')
                ->unique()
                ->after('id')
                ->comment('User phone number in E.164 format (e.g., +1234567890) ');
            $table->string('username')
                ->unique()
                ->after('email')
                ->comment('Unique username for the user, used for public profile identification');
            $table->renameColumn('name', 'display_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn('phone_number');
            $table->dropColumn('username');
            // Revert column rename
            $table->renameColumn('display_name', 'name');
        });
    }
};
