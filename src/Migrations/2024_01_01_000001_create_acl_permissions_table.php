<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', static function (Blueprint $table) {
                $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
                $table->string('name');
                $table->string('description')->nullable();
                $table->boolean('active')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', static function (Blueprint $table) {
                $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
                $table->string('name');
                $table->string('description')->nullable();
                $table->string('module');
                $table->string('controller');
                $table->string('action');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', static function (Blueprint $table) {
                $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
                $table->foreignUuid('role_id')->nullable()->constrained()->onDelete('cascade');;
                $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('cascade');;
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', static function (Blueprint $table) {
                $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
                $table->foreignUuid('permission_id')->constrained()->onDelete('cascade');
                $table->foreignUuid('role_id')->nullable()->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }


}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sse_notify');
    }
};
