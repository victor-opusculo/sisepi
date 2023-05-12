<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ProfessorOdsProposals extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table("professorodsproposals");
        $table
        ->addColumn("professorWorkProposalId", "integer", [ 'null' => false, 'signed' => false ])
        ->addColumn("odsGoals", "json", [ 'null' => false ])
        ->addColumn("odsRelationId", "integer", [ 'null' => true, 'signed' => false ])
        ->addIndex("professorWorkProposalId", ['unique' => true] )
        ->addIndex("odsRelationId")
        ->addForeignKey("professorWorkProposalId", "professorworkproposals", ['id'], [ 'delete' => 'CASCADE', 'update' => 'CASCADE'])
        ->addForeignKey("odsRelationId", "odsrelations", ["id"], [ 'delete' => 'SET_NULL', 'update' => 'CASCADE'])
        ->create();
    }
}
