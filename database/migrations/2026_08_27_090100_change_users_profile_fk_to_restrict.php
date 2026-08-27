<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A exclusão de Perfil passou a ser soft delete (o cascade nunca dispara), mas por
 * defesa em profundidade a FK users.profile_id deixa de ser ON DELETE CASCADE.
 * SQLite não suporta ALTER de FK — lá a proteção fica só no soft delete + guarda no Service.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->foreign('profile_id')->references('id')->on('profiles')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->foreign('profile_id')->references('id')->on('profiles')->cascadeOnDelete();
        });
    }
};
