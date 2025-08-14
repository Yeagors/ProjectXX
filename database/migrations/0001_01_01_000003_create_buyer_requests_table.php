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
        if (!Schema::hasTable('buyer_requests')) {
            Schema::create('buyer_requests', function (Blueprint $table) {
                $table->uuid('req_id')->nullable()->comment('Уникальный айди заявки');
                $table->string('brand')->nullable()->comment('Марка авто');
                $table->string('model')->nullable()->comment('Модель авто');
                $table->string('number')->nullable()->comment('Номер авто');
                $table->string('kpp')->nullable()->comment('Кпп авто');
                $table->string('year')->nullable()->comment('Год авто');
                $table->string('user_phone')->nullable()->comment('Телефон');
                $table->string('user_name')->nullable()->comment('ФИО');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
