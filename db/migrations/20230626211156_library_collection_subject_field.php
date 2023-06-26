<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LibraryCollectionSubjectField extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $table = $this->table('librarycollection');
        $table
        ->addColumn('subject', 'string', [ 'limit' => 400, 'after' => 'pageNumber' ])
        ->removeIndexByName('author')
        ->update();

        $table = $this->table('librarycollection');
        $table
        ->addIndex(['author', 'title', 'cdu', 'cdd', 'isbn', 'publisher_edition', 'provider', 'authorCode', 'subject'], [ 'name' => 'author', 'type' => 'fulltext' ])
        ->update();
    }

    public function down(): void
    {
        $table = $this->table('librarycollection');
        $table
        ->removeIndexByName('author')
        ->removeColumn('subject')
        ->update();

        $table = $this->table('librarycollection');
        $table
        ->addIndex(['author', 'title', 'cdu', 'cdd', 'isbn', 'publisher_edition', 'provider', 'authorCode'], [ 'name' => 'author', 'type' => 'fulltext' ])
        ->update();
    }

}
