<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProfessorInformeRendimentos extends AbstractMigration
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
        $table = $this->table("professors_ir_attachments");
        $table
        ->addColumn('year', 'integer', [ 'null' => false ])
        ->addColumn('professorId', 'integer', [ 'null' => false, 'signed' => false ])
        ->addColumn('fileExtension', 'string', [ 'null' => false, 'limit' => 255 ])
        ->addForeignKey('professorId', 'professors', ['id'], [ 'delete' => 'CASCADE', 'update' => 'CASCADE'] )
        ->create();

        $rowsToAdd =
        [
            [ 'permMod' => 'PROFE', 'permId' => 14, 'permDesc' => 'Docentes: Cadastrar informe de rendimentos' ],
            [ 'permMod' => 'PROFE', 'permId' => 15, 'permDesc' => 'Docentes: Excluir informe de rendimentos' ]
        ];
        $perm = $this->table('permissions');
        $perm->insert($rowsToAdd)->saveData();
    }

    public function down(): void
    {
        $table = $this->table("professors_ir_attachments");
        $table->drop()->save();

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'PROFE' ])
        ->whereInList('permId', [14, 15])
        ->execute();
    }
}
