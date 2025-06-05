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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')->constrained();

            $table->string('question');

            $table->string('value_type')->nullable(); // [multiple_choice](string), [date_picker](date), [input_text](string), [multiple_answer](json)

            $table->json('options')->nullable(); // store array of available options

            $table->timestamps();
            $table->softDeletes();
        });

        # extra credit: store answer
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_question_id')->constrained();

            $table->string('string_value')->nullable(); // store [multiple_choice], [input_text]
            $table->date('date_value')->nullable(); // store date from date_picker
            $table->json('json_value')->nullable(); // store array from [multiple_answer]

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};
