<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $name;
    public $password;
    public $rememberMe = true;

    private $_employee = false;

    public function rules(): array
    {
        return [
            [['name', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'password' => 'Password',
            'rememberMe' => 'Remember Me',
        ];
    }

    public function validatePassword(string $attribute, ?array $params = []): void
    {
        if (!$this->hasErrors()) {
            $employee = $this->getEmployee();

            if (!$employee || !$employee->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect name or password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getEmployee(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getEmployee(): ?Employee
    {
        if ($this->_employee === false) {
            $this->_employee = Employee::findOne(['name' => $this->name]);
        }

        return $this->_employee;
    }
}
