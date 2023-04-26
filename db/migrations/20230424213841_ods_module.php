<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OdsModule extends AbstractMigration
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
    public function up() : void
    {
        $sett = $this->table("settings");
        $sett
        ->changeColumn('value', 'text', [ 'null' => true ])
        ->update();

        $odsData = file_get_contents(__DIR__ . '/../../includes/ods-data.json');
        $odsRows = [[ 'name' => 'ODS_DATA', 'value' => $odsData ]];

        $sett = $this->table("settings");
        $sett->insert($odsRows)->saveData();

        $odsRelations = $this->table("odsrelations");
        $odsRelations
        ->addColumn('name', 'string', [ 'limit' => 255, 'null' => false ])
        ->addColumn('year', 'integer', [ 'null' => false ])
        ->addColumn('odsCodes', 'json', [ 'null' => false ])
        ->addColumn('eventId', 'integer', [ 'null' => true, 'signed' => false ])
        ->addIndex('eventId', [ 'unique' => true ])
        ->addForeignKey('eventId', 'events', 'id', [ 'delete' => 'SET_NULL', 'update' => 'CASCADE'])
        ->addIndex(['name'], [ 'type' => 'fulltext' ])
        ->create();

        $rowsToAdd =
        [
            [ 'permMod' => 'ODSRL', 'permId' => 1, 'permDesc' => 'Relações ODS: Ver ODS' ],
            [ 'permMod' => 'ODSRL', 'permId' => 2, 'permDesc' => 'Relações ODS: Criar Relações ODS' ],
            [ 'permMod' => 'ODSRL', 'permId' => 3, 'permDesc' => 'Relações ODS: Editar Relações ODS' ],
            [ 'permMod' => 'ODSRL', 'permId' => 4, 'permDesc' => 'Relações ODS: Excluir Relações ODS' ]
        ];
        $perm = $this->table('permissions');
        $perm->insert($rowsToAdd)->saveData();
    }

    public function down() : void
    {
        $this->execute("DELETE FROM settings WHERE name = 'ODS_DATA' ");

        $sett = $this->table("settings");
        $sett
        ->changeColumn('value', 'string', [ 'null' => true, 'limit' => 1200 ])
        ->update();

        $odsRelations = $this->table("odsrelations");
        $odsRelations->drop()->save();

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'ODSRL' ])
        ->whereInList('permId', [1, 2, 3, 4])
        ->execute();
    }
}
