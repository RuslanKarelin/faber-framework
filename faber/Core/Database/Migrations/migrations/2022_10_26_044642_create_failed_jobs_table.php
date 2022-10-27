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
        Schema::create('failed_jobs', function (Builder $table) {
            $table->id();
            $table->string('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->default('CURRENT_TIMESTAMP');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
    }
};