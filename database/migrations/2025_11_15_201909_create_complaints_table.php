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
        /*
Table complaints {
  id integer [primary key]
  citizen_id integer  
  agency_id integer  
  assigned_to integer  
  Title string
  description text
  status enum
  created_at timestamp
}*/
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['خدمة', 'سلوك', 'بنية تحتية']);
            $table->enum('priority', ['high', 'low', 'medium'])->default('medium');
            $table->enum('status', ['new', 'in_review', 'in_progress', 'awaiting_info', 'resolved', 'rejected', 'closed'])->default('new');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('address_text')->nullable();
            $table->boolean('is_locked')->nullable();
            $table->foreignId('citizen_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('employee_id')->references('id')->on('users')->onDelete('cascade'); // employee that process the complaint
            $table->foreignId('agency_id')->references('id')->on('government_agencies')->onDelete('cascade'); // employee that process the complaint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
