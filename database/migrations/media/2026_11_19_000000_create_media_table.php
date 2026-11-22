<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Runs against the dedicated `sqlite_media` connection, not the
     * app's default `mongodb` connection — see config/database.php and
     * App\Models\Media for why. `model_id` is `string`, not
     * `unsignedBigInteger` (the vendor stub's default via `morphs()`):
     * owning models are MongoDB documents with string ObjectId keys.
     */
    protected $connection = 'sqlite_media';

    public function up(): void
    {
        // `migrate:fresh` only drops tables/collections on the app's
        // default (`mongodb`) connection — this migration's own table
        // lives on `sqlite_media`, a connection Laravel's fresh-drop
        // step doesn't know about, so a second `migrate:fresh` run would
        // otherwise collide with the still-existing SQLite table.
        Schema::connection($this->getConnection())->dropIfExists('media');

        Schema::connection($this->getConnection())->create('media', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->string('model_id')->index();
            $table->string('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('media');
    }
};
