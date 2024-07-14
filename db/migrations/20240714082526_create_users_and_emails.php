<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersAndEmails extends AbstractMigration
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
        // Create 'users' table
        $table = $this->table('users');
        $table->addColumn('username', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addIndex('username', ['unique' => true])
            ->create();

        // Create 'emails' table
        $table = $this->table('emails');
        $table->addColumn('to', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('subject', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('body', 'text', ['null' => true])
            ->addColumn('sent_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
