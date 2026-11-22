<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use MongoDB\Database;
use MongoDB\Laravel\Connection;

/**
 * Index-only — Mongo is schemaless, so there is no Blueprint column
 * list to declare here, and Blueprint's ->default() is a no-op on this
 * connection. Native driver createIndex() is used instead of
 * Schema::create()/Blueprint so unique/sparse options actually apply.
 */
return new class extends Migration
{
    public function up(): void
    {
        $db = $this->database();

        $db->selectCollection('personnel')->createIndex(
            ['callsign_lower' => 1],
            ['name' => 'personnel_callsign_lower_unique', 'unique' => true],
        );
        $db->selectCollection('personnel')->createIndex(
            ['callsign' => 1],
            ['name' => 'personnel_callsign'],
        );
        $db->selectCollection('personnel')->createIndex(
            ['is_published' => 1, 'sort' => 1],
            ['name' => 'personnel_published_sort'],
        );
        $db->selectCollection('personnel')->createIndex(
            ['era_id' => 1],
            ['name' => 'personnel_era_id', 'sparse' => true],
        );
        $db->selectCollection('personnel')->createIndex(
            ['is_synthetic' => 1],
            ['name' => 'personnel_is_synthetic'],
        );

        $db->selectCollection('activities')->createIndex(
            ['slug' => 1],
            ['name' => 'activities_slug_unique', 'unique' => true],
        );
        $db->selectCollection('activities')->createIndex(
            ['is_published' => 1, 'occurred_on' => -1],
            ['name' => 'activities_published_occurred'],
        );
        $db->selectCollection('activities')->createIndex(
            ['activity_category_id' => 1, 'occurred_on' => -1],
            ['name' => 'activities_category_occurred'],
        );
        $db->selectCollection('activities')->createIndex(
            ['related_member_callsigns' => 1],
            ['name' => 'activities_related_member_callsigns'],
        );
        $db->selectCollection('activities')->createIndex(
            ['is_synthetic' => 1],
            ['name' => 'activities_is_synthetic'],
        );

        $db->selectCollection('activity_categories')->createIndex(
            ['slug' => 1],
            ['name' => 'activity_categories_slug_unique', 'unique' => true],
        );
        $db->selectCollection('activity_categories')->createIndex(
            ['is_published' => 1, 'sort' => 1],
            ['name' => 'activity_categories_published_sort'],
        );

        $db->selectCollection('media_items')->createIndex(
            ['key' => 1],
            ['name' => 'media_items_key_unique', 'unique' => true],
        );
        $db->selectCollection('media_items')->createIndex(
            ['is_published' => 1, 'sort' => 1],
            ['name' => 'media_items_published_sort'],
        );
        $db->selectCollection('media_items')->createIndex(
            ['is_published' => 1, 'category' => 1, 'sort' => 1],
            ['name' => 'media_items_published_category_sort'],
        );
        $db->selectCollection('media_items')->createIndex(
            ['is_published' => 1, 'type' => 1, 'sort' => 1],
            ['name' => 'media_items_published_type_sort'],
        );
        $db->selectCollection('media_items')->createIndex(
            ['related_activity_slug' => 1],
            ['name' => 'media_items_related_activity_slug', 'sparse' => true],
        );
        $db->selectCollection('media_items')->createIndex(
            ['is_synthetic' => 1],
            ['name' => 'media_items_is_synthetic'],
        );

        $db->selectCollection('publications')->createIndex(
            ['slug' => 1],
            ['name' => 'publications_slug_unique', 'unique' => true],
        );
        $db->selectCollection('publications')->createIndex(
            ['is_published' => 1, 'published_on' => -1],
            ['name' => 'publications_published_on'],
        );
        $db->selectCollection('publications')->createIndex(
            ['publication_category_id' => 1, 'published_on' => -1],
            ['name' => 'publications_category_published_on'],
        );
        $db->selectCollection('publications')->createIndex(
            ['author_callsign' => 1],
            ['name' => 'publications_author_callsign'],
        );
        $db->selectCollection('publications')->createIndex(
            ['related_activity_slug' => 1],
            ['name' => 'publications_related_activity_slug', 'sparse' => true],
        );
        $db->selectCollection('publications')->createIndex(
            ['is_synthetic' => 1],
            ['name' => 'publications_is_synthetic'],
        );

        $db->selectCollection('publication_categories')->createIndex(
            ['slug' => 1],
            ['name' => 'publication_categories_slug_unique', 'unique' => true],
        );
        $db->selectCollection('publication_categories')->createIndex(
            ['is_published' => 1, 'sort' => 1],
            ['name' => 'publication_categories_published_sort'],
        );

        $db->selectCollection('eras')->createIndex(
            ['key' => 1],
            ['name' => 'eras_key_unique', 'unique' => true],
        );
        $db->selectCollection('eras')->createIndex(
            ['is_published' => 1, 'sort' => 1],
            ['name' => 'eras_published_sort'],
        );

        $db->selectCollection('weapon_variants')->createIndex(
            ['key' => 1],
            ['name' => 'weapon_variants_key_unique', 'unique' => true],
        );
        $db->selectCollection('weapon_variants')->createIndex(
            ['is_published' => 1, 'category' => 1, 'sort' => 1],
            ['name' => 'weapon_variants_published_category_sort'],
        );

        $db->selectCollection('pages')->createIndex(
            ['key' => 1],
            ['name' => 'pages_key_unique', 'unique' => true],
        );

        $db->selectCollection('page_blocks')->createIndex(
            ['block_key' => 1],
            ['name' => 'page_blocks_block_key_unique', 'unique' => true],
        );
        $db->selectCollection('page_blocks')->createIndex(
            ['page_key' => 1, 'is_published' => 1, 'sort' => 1],
            ['name' => 'page_blocks_page_published_sort'],
        );
    }

    public function down(): void
    {
        $collections = [
            'personnel', 'activities', 'activity_categories', 'media_items',
            'publications', 'publication_categories', 'eras', 'weapon_variants',
            'pages', 'page_blocks',
        ];

        foreach ($collections as $name) {
            try {
                $this->database()->selectCollection($name)->dropIndexes();
            } catch (Throwable) {
                // collection may not exist yet — fine on a fresh database
            }
        }
    }

    private function database(): Database
    {
        $connection = DB::connection('mongodb');
        throw_unless($connection instanceof Connection, RuntimeException::class,
            'The mongodb connection must use the MongoDB Laravel driver.');

        return $connection->getDatabase();
    }
};
