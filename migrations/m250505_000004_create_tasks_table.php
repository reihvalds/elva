<?php

use yii\db\Migration;

class m250505_000004_create_tasks_table extends Migration
{
    public function up(): void
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'task' => $this->string(255)->notNull(),
            'employee_id' => $this->integer()->null(),
            'construction_site_id' => $this->integer()->null(),
            'date' => $this->date()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function down(): void
    {
        $this->dropTable('{{%tasks}}');
    }
} 