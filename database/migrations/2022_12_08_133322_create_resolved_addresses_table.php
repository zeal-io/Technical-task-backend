<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ResolvedAddress;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resolved_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 3);
            $table->string('city');
            $table->string('street');
            $table->integer('postcode');
            $table->string('lat', 32)->nullable();
            $table->string('lng', 32)->nullable();
            $table->enum('source', [ResolvedAddress::SOURCE_GOOGLE, ResolvedAddress::SOURCE_HERE]);
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
        Schema::dropIfExists('resolved_addresses');
    }
};
