<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefreshTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('token', 64);
            $table->timestamp('expired_at')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'user_id',
                'token',
            ], 'user_token_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refresh_tokens');
    }
}
