<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateObserverAssignmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'periode_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'observer_guru_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'target_guru_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'completed'],
                'default'    => 'pending',
            ],
            'catatan_kepsek' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('periode_id', 'periodes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('observer_guru_id', 'gurus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('target_guru_id', 'gurus', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('penugasan_observers', true);
    }

    public function down()
    {
        $this->forge->dropTable('penugasan_observers', true);
    }
}
