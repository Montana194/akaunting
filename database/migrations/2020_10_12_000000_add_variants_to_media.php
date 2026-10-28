<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('media', 'variant_name')) {
            Schema::table('media', function (Blueprint $table) {
                $table->string('variant_name', 255)
                    ->after('size')
                    ->nullable();
            });
        }

        if (! Schema::hasColumn('media', 'original_media_id')) {
            Schema::table('media', function (Blueprint $table) {
                $table->integer('original_media_id')
                    ->unsigned()
                    ->after('variant_name')
                    ->nullable();
            });
        }

        $connection = Schema::getConnection();
        $tableName = $connection->getTablePrefix() . 'media';

        // Inspect the actual table without applying Laravel's prefix to system metadata.
        $foreignKeyExists = collect($connection->getDoctrineSchemaManager()->listTableForeignKeys($tableName))
            ->contains(fn ($key) => $key->getLocalColumns() === ['original_media_id']
                && $key->getForeignTableName() === $tableName);

        if (! $foreignKeyExists) {
            Schema::table('media', function (Blueprint $table) {
                $table->foreign('original_media_id')
                    ->references('id')->on('media')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = Schema::getConnection();
        $tableName = $connection->getTablePrefix() . 'media';
        $foreignKeys = $connection->getDoctrineSchemaManager()->listTableForeignKeys($tableName);
        Schema::table(
            'media',
            function (Blueprint $table) use ($foreignKeys) {
                if (DB::getDriverName() !== 'sqlite') {
                    foreach ($foreignKeys as $key) {
                        if ($key->getLocalColumns() === ['original_media_id']) {
                            $table->dropForeign($key->getName());
                        }
                    }
                }
                $table->dropColumn('original_media_id');
            }
        );
        Schema::table(
            'media',
            function (Blueprint $table) {
                $table->dropColumn('variant_name');
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('mediable.connection_name', parent::getConnection());
    }
};
