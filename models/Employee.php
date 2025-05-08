<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\IdentityInterface;

class Employee extends ActiveRecord implements IdentityInterface
{
    public $password_repeat;
    private $_password;

    public static function tableName(): string
    {
        return '{{%employees}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'surname', 'birthday', 'role'], 'required'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['access_level', 'manager_id'], 'integer'],
            [['name', 'surname', 'role'], 'string', 'max' => 255],
            [['access_level'], 'default', 'value' => 1],
            [['access_level'], 'in', 'range' => [1, 2, 3]],
            [['role'], 'in', 'range' => array_keys(EmployeeRoles::getRolesList())],
            [['password'], 'required', 'on' => 'create'],
            [['password'], 'string', 'min' => 6],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'on' => ['create', 'update']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['manager_id' => 'id']],
            [['manager_id'], 'validateManagerId'],
        ];
    }

    public function validateManagerId($attribute, $params): void
    {
        if (!$this->hasErrors() && $this->$attribute === $this->id) {
            $this->addError($attribute, 'An employee cannot be their own manager.');
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'birthday' => 'Birthday',
            'access_level' => 'Access Level',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'password' => 'Password',
            'password_repeat' => 'Repeat Password',
            'manager_id' => 'Manager',
        ];
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->password) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isEmployee(): bool
    {
        return $this->hasRole(EmployeeRoles::ROLE_EMPLOYEE);
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole(EmployeeRoles::ROLE_ADMIN);
    }
    
    public function isManager(): bool
    {
        return $this->hasRole(EmployeeRoles::ROLE_MANAGER);
    }
    
    public function getManagedConstructionSites(): ActiveQuery
    {
        return $this->hasMany(ConstructionSite::class, ['manager_id' => 'id']);
    }
    
    public function managesConstructionSite(ActiveRecord $constructionSite): ?ActiveRecord
    {
        return $this->getManagedConstructionSites()
            ->where(['id' => $constructionSite->id])
            ->one();
    }

    public function getManager(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'manager_id']);
    }

    public function getSubordinates(): ActiveQuery
    {
        return $this->hasMany(Employee::class, ['manager_id' => 'id']);
    }
    
    public function isSubordinate(int|Employee $employee): bool
    {
        $employeeId = $employee instanceof Employee ? $employee->id : $employee;
        return $this->getSubordinates()
            ->where(['id' => $employeeId])
            ->exists();
    }

    public function getAllSubordinates(): array
    {
        return Employee::find()
            ->select(['id'])
            ->where(['manager_id' => $this->id])
            ->all();
    }

    /**
     * @throws Exception
     */
    public function addSubordinate(Employee $subordinate): bool
    {
        $subordinate->manager_id = $this->id;
        return $subordinate->save(false);
    }

    /**
     * @throws Exception
     */
    public function removeSubordinate(Employee $subordinate): bool
    {
        $subordinate->manager_id = null;
        return $subordinate->save(false);
    }

    public static function findIdentity($id): ?Employee
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?Employee
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): ?string
    {
        return null;
    }

    public function validateAuthKey($authKey): bool
    {
        return false;
    }
} 