<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('requests')) {
            Schema::create('requests', function (Blueprint $table) {
                $table->uuid('id')->primary();
                //$table->uuid('req_id')->nullable()->comment('Уникальный айди заявки');
                $table->string('brand')->nullable()->comment('Марка авто');
                $table->string('model')->nullable()->comment('Модель авто');
                $table->string('license_plate')->nullable()->comment('Номер авто');
                $table->string('kpp')->nullable()->comment('Кпп авто');
                $table->string('year')->nullable()->comment('Год авто');
                $table->string('phone')->nullable()->comment('Телефон');
                $table->string('first_name')->nullable()->comment('ФИО');
                $table->string('middle_name')->nullable()->comment('ФИО');
                $table->string('last_name')->nullable()->comment('ФИО');
                $table->string('status')->default('new')->comment('Статус');
                $table->integer('amount')->default('0')->comment('Оценка авто');
                $table->string('data')->nullable()->comment('Результаты осмотра');
                $table->timestamps();
            });
        }
        Schema::table('requests', function (Blueprint $table) {
            if (!Schema::hasColumn('requests', 'data')) {
                $table->string('data')->nullable()->after('amount')->comment('Результаты осмотра');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
