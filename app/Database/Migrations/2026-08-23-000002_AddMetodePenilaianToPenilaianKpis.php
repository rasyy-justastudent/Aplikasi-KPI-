<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetodePenilaianToPenilaianKpis extends Migration
{
    public function up()
    {
        $fields = [
            'metode_jenis'               => ['type' => 'TEXT', 'null' => true],
            'metode_proporsi'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'metode_contoh_penyesuaian'  => ['type' => 'TEXT', 'null' => true],
            'metode_rubrik_status'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'metode_file_pdf'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ];
        $this->forge->addColumn('penilaian_kpis', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('penilaian_kpis', [
            'metode_jenis',
            'metode_proporsi',
            'metode_contoh_penyesuaian',
            'metode_rubrik_status',
            'metode_file_pdf'
        ]);
    }
}
