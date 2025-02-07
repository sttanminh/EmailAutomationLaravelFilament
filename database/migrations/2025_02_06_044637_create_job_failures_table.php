<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_failures', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('job_name'); // Job class name
            $table->unsignedBigInteger('pdf_link_id')->nullable(); // ID of the failed URL
            $table->string('exception')->nullable(); // Error message
            $table->timestamp('failed_at')->useCurrent(); // When the failure happened
            $table->timestamps();

            // Foreign key to track which PDF link failed
            $table->foreign('pdf_link_id')->references('id')->on('pdf_links')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('job_failures');
    }
};
