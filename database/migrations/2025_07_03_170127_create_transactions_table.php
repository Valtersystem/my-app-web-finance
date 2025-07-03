<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->string('type'); // 'income' or 'expense'
            $table->string('currency', 3)->default('EUR');
            $table->text('observation')->nullable();
            $table->date('date');
            $table->boolean('paid')->default(true);
            $table->boolean('ignored')->default(false);

            // Para parcelas
            $table->foreignId('parent_id')->nullable()->constrained('transactions')->onDelete('cascade');
            $table->integer('installment_number')->nullable();
            $table->integer('total_installments')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
