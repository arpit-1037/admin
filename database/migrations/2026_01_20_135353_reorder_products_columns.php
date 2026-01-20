<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Move category_id right after id
            $table->foreignId('category_id')->change()->after('id');

            // Move name after category_id
            $table->string('name')->change()->after('category_id');

            // Move slug after name
            $table->string('slug')->change()->after('name');

            // Move description after slug
            $table->text('description')->nullable()->change()->after('slug');

            // Move price after description
            $table->decimal('price', 10, 2)->change()->after('description');

            // Move stock after price
            $table->integer('stock')->change()->after('price');

            // Move is_active after stock
            $table->boolean('is_active')->change()->after('stock');

            // Move timestamps to the end
            $table->timestamp('created_at')->change()->after('is_active');
            $table->timestamp('updated_at')->change()->after('created_at');
        });
    }

    public function down(): void
    {
        // No rollback needed for column order
        // Column order rollback is intentionally omitted
    }
};
