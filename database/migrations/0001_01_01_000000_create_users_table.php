<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->uuid('user_id')->comment('Уникальный айди пользователя');
                $table->string('first_name')->comment('Имя');
                $table->string('last_name')->comment('Фамилия');
                $table->string('middle_name')->nullable()->comment('Отчество');
                $table->string('user_phone')->comment('Телефон');
                $table->string('role')->comment('Роль');
                $table->date('bd')->comment('Дата рождения');
                $table->string('password')->comment('Пароль');
                $table->timestamps();
            });
        }
    }
};
