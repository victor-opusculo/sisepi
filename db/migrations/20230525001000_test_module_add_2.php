<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TestModuleAdd2 extends AbstractMigration
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
        $table = $this->table('eventcompletedtests');
        $table
        ->addColumn('sentDateTime', 'datetime', [ 'after' => 'testData', 'null' => false ])
        ->update();

        $rowsToAdd =
        [
            [ 'permMod' => 'EVTST', 'permId' => 6, 'permDesc' => 'Avaliações de inscritos: Excluir avaliações feitas' ]
        ];
        $perm = $this->table('permissions');
        $perm->insert($rowsToAdd)->saveData();
    }

    public function down(): void
    {
        $table = $this->table('eventcompletedtests');
        $table
        ->removeColumn('sentDateTime')
        ->update();

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'EVTST' ])
        ->where([ 'permId' => 6 ])
        ->execute();
    }
}
