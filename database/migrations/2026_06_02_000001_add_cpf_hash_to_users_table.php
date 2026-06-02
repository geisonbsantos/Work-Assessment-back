<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCpfHashToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf_hash', 64)->nullable()->unique();
        });

        $users = DB::table('users')->select('id', 'cpf')->get();

        foreach ($users as $user) {
            $cpf = preg_replace('/\D/', '', $user->cpf);
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'cpf_hash' => hash('sha256', $cpf),
                    'cpf' => Crypt::encryptString($cpf),
                ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('cpf');
            $table->dropUnique(['cpf_hash']);
            $table->dropColumn('cpf_hash');
        });
    }
}
