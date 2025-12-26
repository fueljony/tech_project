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
        Schema::table('surveys', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft')->after('description');
            $table->json('settings')->nullable()->after('status');
        });

        Schema::table('survey_questions', function (Blueprint $table) {
            $table->boolean('required')->default(false)->after('options');
            $table->integer('order')->default(0)->after('required');
            $table->json('validation_rules')->nullable()->after('order');
            $table->text('help_text')->nullable()->after('validation_rules');
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->foreignId('survey_id')->after('id')->constrained();
            $table->json('metadata')->nullable()->after('json_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['description', 'status', 'settings']);
        });

        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropColumn(['required', 'order', 'validation_rules', 'help_text']);
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropForeign(['survey_id']);
            $table->dropColumn(['survey_id', 'metadata']);
        });
    }
};
