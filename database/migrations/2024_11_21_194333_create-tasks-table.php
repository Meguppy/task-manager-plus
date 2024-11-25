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
        // tasks テーブル作成
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT付きのBIGINT型
            $table->string('name', 15); // VARCHAR(15)
            $table->date('deadline_at')->nullable($value = true); // DATE型
            $table->unsignedBigInteger('user_id')->nullable($value = true);
            $table->dateTime('done_at')->nullable($value = true);
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
