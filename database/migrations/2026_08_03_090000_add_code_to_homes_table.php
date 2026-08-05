<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('homes', 'code')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->string('code')->nullable()->after('id');
            });
        }

        $homes = DB::table('homes')->whereNull('code')->orWhere('code', '')->get();

        foreach ($homes as $home) {
            $code = strtoupper(substr(md5(uniqid()), 0, 4));

            while (DB::table('homes')->where('code', $code)->exists()) {
                $code = strtoupper(substr(md5(uniqid()), 0, 4));
            }

            DB::table('homes')->where('id', $home->id)->update(['code' => $code]);
        }

        if (Schema::hasColumn('homes', 'code')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->string('code')->nullable(false)->change();
            });
        }

        if (Schema::hasColumn('homes', 'code') && ! Schema::hasIndex('homes', 'homes_code_unique')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->unique('code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('homes', 'code')) {
            if (Schema::hasIndex('homes', 'homes_code_unique')) {
                Schema::table('homes', function (Blueprint $table) {
                    $table->dropUnique(['code']);
                });
            }

            Schema::table('homes', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }
};
