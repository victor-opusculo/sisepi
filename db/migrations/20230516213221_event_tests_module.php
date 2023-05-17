<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EventTestsModule extends AbstractMigration
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
        ->addColumn('subscriptionId', 'integer', [ 'null' => true, 'signed' => false ])
        ->addColumn('email', 'varbinary', [ 'null' => true, 'limit' => 280 ])
        ->addColumn('eventId', 'integer', [ 'null' => true, 'signed' => false ])
        ->addColumn('testData', 'json', [ 'null' => false ])
        ->addIndex('subscriptionId')
        ->addIndex('eventId')
        ->addForeignKey('subscriptionId', 'subscriptionstudentsnew', ['id'], [ 'delete' => 'CASCADE', 'update' => 'CASCADE'] )
        ->addForeignKey('eventId', 'events', ['id'], [ 'delete' => 'SET_NULL', 'update' => 'CASCADE'] )
        ->create();

        $rowsToAdd =
        [
            [ 'permMod' => 'EVTST', 'permId' => 1, 'permDesc' => 'Avaliações de inscritos: Ver modelos de avaliação' ],
            [ 'permMod' => 'EVTST', 'permId' => 2, 'permDesc' => 'Avaliações de inscritos: Criar modelos de avaliação' ],
            [ 'permMod' => 'EVTST', 'permId' => 3, 'permDesc' => 'Avaliações de inscritos: Editar modelos de avaliação' ],
            [ 'permMod' => 'EVTST', 'permId' => 4, 'permDesc' => 'Avaliações de inscritos: Excluir modelos de avaliação' ],
            [ 'permMod' => 'EVTST', 'permId' => 5, 'permDesc' => 'Avaliações de inscritos: Ver avaliações feitas' ]
        ];
        $perm = $this->table('permissions');
        $perm->insert($rowsToAdd)->saveData();

        $table = $this->table('events');
        $table
        ->addColumn('testTemplateId', 'integer', [ 'null' => true, 'after' => 'surveyTemplateId' ])
        ->addIndex('testTemplateId')
        ->update();
    }

    public function down() : void
    {
        $table = $this->table('eventcompletedtests');
        $table->drop()->save();

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('permissions')
        ->where([ 'permMod' => 'EVTST' ])
        ->whereInList('permId', [1, 2, 3, 4, 5])
        ->execute();

        $table = $this->table('events');
        $table
        ->removeColumn('testTemplateId')
        ->save();
    }
}
