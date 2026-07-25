<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            $this->upForSqlite();
            return;
        }

        // MySQL / PostgreSQL: بسيطة ومباشرة
        Schema::table('beneficiary_organization', function (Blueprint $table) {
            $table->id()->first();
        });
    }

    public function down(): void
    {
        Schema::table('beneficiary_organization', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }

    /**
     * SQLite ما بيدعم إضافة PRIMARY KEY AUTOINCREMENT عبر ALTER TABLE
     * على جدول فيه بيانات، فبنعيد بناء الجدول بنفس تعريفه الأصلي + عمود id.
     */
    protected function upForSqlite(): void
    {
        $originalSql = DB::selectOne(
            "SELECT sql FROM sqlite_master WHERE type = 'table' AND name = 'beneficiary_organization'"
        )->sql;

        Schema::rename('beneficiary_organization', 'beneficiary_organization_old');

        // نحقن تعريف عمود id فور بعد القوس الأولى بتعريف الجدول الأصلي
        $insertPos = strpos($originalSql, '(');
        $newSql = substr($originalSql, 0, $insertPos + 1)
            . '"id" integer primary key autoincrement not null, '
            . substr($originalSql, $insertPos + 1);

        DB::statement($newSql);

        $columns = Schema::getColumnListing('beneficiary_organization_old');
        $columnList = implode(', ', array_map(fn ($c) => "\"$c\"", $columns));

        DB::statement(
            "INSERT INTO beneficiary_organization ($columnList) SELECT $columnList FROM beneficiary_organization_old"
        );

        Schema::drop('beneficiary_organization_old');
    }
};
