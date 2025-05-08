<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Task extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%tasks}}';
    }

    public function rules(): array
    {
        return [
            [['task', 'employee_id', 'construction_site_id', 'date'], 'required'],
            [['employee_id', 'construction_site_id'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['task'], 'string', 'max' => 255],
            [['employee_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Employee::class,
                'targetAttribute' => ['employee_id' => 'id']],
            [['construction_site_id'], 'exist', 'skipOnError' => true,
                'targetClass' => ConstructionSite::class,
                'targetAttribute' => ['construction_site_id' => 'id']],
            [['employee_id'], 'validateEmployeeRole'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'task' => 'Task',
            'employee_id' => 'Employee',
            'construction_site_id' => 'Construction Site',
            'date' => 'Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function validateEmployeeRole(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $employee = Employee::findOne($this->$attribute);
            if ($employee && $employee->role !== EmployeeRoles::ROLE_EMPLOYEE) {
                $this->addError($attribute, 'Only employees with the employee role can be assigned tasks.');
            }
        }
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getEmployee(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }

    public function getConstructionSite(): ?ActiveQuery
    {
        return $this->hasOne(ConstructionSite::class, ['id' => 'construction_site_id']);
    }
} 