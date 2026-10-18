<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm các cột auth vào bảng users (email, password, email_verified_at).
 *
 * Bảng users gốc chỉ phục vụ profile (first_name/last_name/nickname/...),
 * không có email/password, nên cần migration này để hỗ trợ login/đăng ký.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->nullable()->after('nickname');
            $table->string('password')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->rememberToken()->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'email_verified_at', 'remember_token']);
        });
    }
};
