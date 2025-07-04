<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Alterações aqui:
            $table->foreignId('account_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('institution_id')->nullable()->constrained()->onDelete('set null');

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->nullable();
            $table->decimal('initial_balance', 15, 2)->default(0);
            $table->boolean('include_in_dashboard')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
