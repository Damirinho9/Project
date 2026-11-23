<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title', 180);
            $table->text('description');
            $table->string('status', 20)->default('new')->index();
            $table->date('date_incident');
            $table->string('contact', 20);
            $table->text('review')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('reports'); }
};
