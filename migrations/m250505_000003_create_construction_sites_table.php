<?php

use yii\db\Migration;

class m250505_000003_create_construction_sites_table extends Migration
{
    public function up(): void
    {
        $this->createTable('{{%construction_sites}}', [
            'id' => $this->primaryKey(),
            'location' => $this->string(255)->notNull(),
            'quadrature' => $this->decimal(10, 2)->notNull(),
            'access_level' => $this->integer()->notNull()->defaultValue(1),            
            'manager_id' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function down(): void
    {
        $this->dropTable('{{%construction_sites}}');
    }
} 