<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LogReportPermissions extends AbstractMigration
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
        $rowsToAdd =
        [
            [ 'permMod' => 'LOG', 'permId' => 1, 'permDesc' => 'Logs: Ver logs do sistema' ],
            [ 'permMod' => 'LOG', 'permId' => 2, 'permDesc' => 'Logs: Excluir arquivo de log do sistema' ]
        ];
        $perm = $this->table('permissions');
        $perm->insert($rowsToAdd)->saveData();
    }

    public function down(): void
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'LOG' ])
        ->whereInList('permId', [1, 2])
        ->execute();
    }
}
