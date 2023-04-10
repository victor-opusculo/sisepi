<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class BudgetModuleMigration extends AbstractMigration
{
    
     /* More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     */
    public function up(): void
    {
        $table = $this->table('budgetentries');
        $table
        ->addColumn('date', 'date', [ 'null' => false ])
        ->addColumn('category', 'integer', [ 'null' => true, 'signed' => false ])
        ->addColumn('details', 'string', [ 'null' => true, 'length' => 255 ])
        ->addColumn('value', 'decimal', [ 'null' => false, 'precision' => 12, 'scale' => 2, 'signed' => true ])
        ->addColumn('eventId', 'integer', [ 'null' => true, 'signed' => false ])
        ->addColumn('professorWorkSheetId', 'integer', [ 'null' => true, 'signed' => true ])
        ->addIndex('details', [ 'type' => 'fulltext'])
        ->addIndex('date')
        ->addIndex(['eventId'])
        ->addIndex(['professorWorkSheetId'])
        ->addForeignKey('eventId', 'events', 'id', [ 'delete' => 'SET_NULL', 'update' => 'CASCADE'])
        ->addForeignKey('professorWorkSheetId', 'professorworksheets', 'id', [ 'delete' => 'SET_NULL', 'update' => 'CASCADE' ] )
        ->save();

        $enumTable = $this->table('enums');
        $rowsToAdd =
        [
            [ 'type' => 'BUDGETCAT', 'id' => 1, 'value' => 'Receita' ],
            [ 'type' => 'BUDGETCAT', 'id' => 2, 'value' => 'Despesa com docente' ],
            [ 'type' => 'BUDGETCAT', 'id' => 3, 'value' => 'Despesa com filmagem/gravação' ],
            [ 'type' => 'BUDGETCAT', 'id' => 4, 'value' => 'Despesa com o Coral' ],
            [ 'type' => 'BUDGETCAT', 'id' => 5, 'value' => 'Despesa com gráfica' ],
            [ 'type' => 'BUDGETCAT', 'id' => 6, 'value' => 'Despesa com publicações próprias' ],
            [ 'type' => 'BUDGETCAT', 'id' => 7, 'value' => 'Devolução do fim do exercício' ]
        ];
        $enumTable->insert($rowsToAdd)->saveData();
    }

    public function down() : void
    {
        $table = $this->table('budgetentries');
        $table->drop()->save();

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
        ->delete('enums')
        ->where([ 'type' => 'BUDGETCAT' ])
        ->whereInList('id', [ 1, 2, 3, 4, 5, 6, 7 ])
        ->execute();
    }
}
