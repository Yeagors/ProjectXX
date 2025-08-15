<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_id');
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('kpp'); // Тип коробки передач
            $table->string('license_plate');
            $table->integer('mileage');
            $table->decimal('start_price', 12, 2);
            $table->decimal('current_price', 12, 2);
            $table->decimal('current_bid', 12, 2)->nullable();
            $table->decimal('bid_step', 12, 2);
            $table->decimal('service_fee', 5, 2); // Комиссия в процентах
            $table->json('inspection_data')->nullable(); // Данные осмотра
            $table->string('status')->default('active'); // active, completed, canceled
            $table->timestamp('start_time')->useCurrent();
            $table->dateTime('start_date')->useCurrent();
            $table->timestamp('end_time')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
        Schema::table('auctions', function (Blueprint $table) {
            if (!Schema::hasColumn('auctions', 'current_bid')) {
                $table->decimal('current_bid', 12, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('auctions');
        Schema::enableForeignKeyConstraints();
    }
};
