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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_street');
            $table->string('company_city');
            $table->string('company_country');
            $table->string('ico');
            $table->string('dic');
            $table->string('ic_dph')->nullable();
            $table->string('iban');
            $table->string('bic');
            $table->string('email');
            $table->string('phone');
            $table->string('default_currency_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
