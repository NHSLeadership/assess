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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->enum('element', [
                'button',
                'color',
                'date',
                'email',
                'file',
                'hidden',
                'image',
                'month',
                'number',
                'password',
                'range',
                'reset',
                'search',
                'submit',
                'tel',
                'text',
                'time',
                'url',
                'week',
                'datalist',
                'textarea',
                'checkbox',
                'radio',
                'select',
            ]);
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('hint')->nullable();
            $table->unsignedSmallInteger('order')->nullable();
            $table->string('defaults')->nullable();
            $table->string('minLength')->nullable();
            $table->string('maxLength')->nullable();
            $table->string('required')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
