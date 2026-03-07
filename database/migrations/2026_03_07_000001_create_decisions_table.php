<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('initiative_id')->nullable()->constrained('initiatives')->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('context');
            $table->text('decision');
            $table->text('consequences')->nullable();
            $table->string('status')->default('proposed');
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
            $table->index('initiative_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
