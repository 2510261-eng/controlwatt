<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('code')->nullable()->after('id');
            });
        }

        $users = DB::table('users')->where(function ($query) {
            $query->whereNull('code')->orWhere('code', '');
        })->get();

        foreach ($users as $user) {
            $code = strtoupper(substr(md5(uniqid()), 0, 4));

            while (DB::table('users')->where('code', $code)->exists()) {
                $code = strtoupper(substr(md5(uniqid()), 0, 4));
            }

            DB::table('users')->where('id', $user->id)->update(['code' => $code]);
        }

        if (Schema::hasColumn('users', 'code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('code')->nullable(false)->change();
            });
        }

        if (Schema::hasColumn('users', 'code') && ! Schema::hasIndex('users', 'users_code_unique')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'code')) {
            if (Schema::hasIndex('users', 'users_code_unique')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropUnique(['code']);
                });
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }
};
