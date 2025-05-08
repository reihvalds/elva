<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ConstructionSite extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%construction_sites}}';
    }

    public function rules(): array
    {
        return [
            [['location', 'quadrature'], 'required'],
            [['quadrature'], 'number'],
            [['access_level', 'manager_id'], 'integer'],
            [['location'], 'string', 'max' => 255],
            [['access_level'], 'default', 'value' => 1],
            [['access_level'], 'in', 'range' => [1, 2, 3]],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['manager_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'location' => 'Location',
            'quadrature' => 'Quadrature',
            'access_level' => 'Access level',
            'manager_id' => 'Manager',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getManager(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'manager_id']);
    }
    
    public function isManager(int $employeeId): bool
    {
        return $this->manager_id === $employeeId;
    }
} 