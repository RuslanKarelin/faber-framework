<?php

use Faber\Core\Database\Migrations\Migration;
use Faber\Core\Facades\Schema;
use Faber\Core\Contracts\Database\Migrations\Builder;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('migrations', function (Builder $table) {
            $table->id();
            $table->string('migration');
            $table->integer('batch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('migrations');
    }
};