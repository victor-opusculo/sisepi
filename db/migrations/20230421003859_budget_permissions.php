<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class BudgetPermissions extends AbstractMigration
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
        $enumTable = $this->table('permissions');
        $rowsToAdd =
        [
            [ 'permMod' => 'BUDGT', 'permId' => 1, 'permDesc' => 'Orçamento: Ver dotações' ],
            [ 'permMod' => 'BUDGT', 'permId' => 2, 'permDesc' => 'Orçamento: Criar dotações' ],
            [ 'permMod' => 'BUDGT', 'permId' => 3, 'permDesc' => 'Orçamento: Editar dotações' ],
            [ 'permMod' => 'BUDGT', 'permId' => 4, 'permDesc' => 'Orçamento: Excluir dotações' ],
            [ 'permMod' => 'ENUM', 'permId' => 4, 'permDesc' => 'Editar enumerador: Orçamento' ]
        ];
        $enumTable->insert($rowsToAdd)->saveData();
    }

    public function down() : void
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'BUDGT' ])
        ->whereInList('permId', [1, 2, 3, 4])
        ->execute();

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'ENUM' ])
        ->where([ 'permId' => 4 ])
        ->execute();
    }
}
