<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('customer_reference')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_email');
            $table->string('receiver_mobile');
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('sender_mobile');
            $table->string('collection_address_street');
            $table->string('collection_address_suburb');
            $table->string('collection_address_city');
            $table->string('collection_address_postcode');
            $table->string('collection_address_province');
            $table->string('collection_instructions');
            $table->string('delivery_address_street');
            $table->string('delivery_address_suburb');
            $table->string('delivery_address_city');
            $table->string('delivery_address_postcode');
            $table->string('delivery_address_province');
            $table->string('delivery_instructions');
            $table->string("waybill_no")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};
