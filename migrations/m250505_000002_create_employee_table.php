<?php

use yii\db\Migration;

class m250505_000002_create_employee_table extends Migration
{
    public function up(): void
    {
        $this->createTable('{{%employees}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'surname' => $this->string(255)->notNull(),
            'birthday' => $this->date()->notNull(),
            'access_level' => $this->integer()->notNull()->defaultValue(1),
            'role' => $this->string(255)->notNull(),
            'manager_id' => $this->integer()->defaultValue(null),	
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'password' => $this->string(255)->notNull(),
        ]);
    }

    public function down(): void
    {
        $this->dropTable('{{%employee}}');
    }
} 