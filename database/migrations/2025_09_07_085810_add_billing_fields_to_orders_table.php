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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('billing_name')->after('customer_id')->nullable();
            $table->string('billing_email')->after('billing_name')->nullable();
            $table->string('billing_phone')->after('billing_email')->nullable();
            $table->string('billing_address')->after('billing_phone')->nullable();
            $table->string('billing_nif')->after('billing_address')->nullable();

            // Campo para método de pago
            $table->enum('payment_method', [
                'Transferencia Bancaria',
                'Efectivo',
                'Bizum',
                'Tarjeta de Crédito'
            ])->after('customer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'billing_name',
                'billing_email',
                'billing_phone',
                'billing_address',
                'payment_method',
                'billing_nif',
            ]);
        });
    }
};
