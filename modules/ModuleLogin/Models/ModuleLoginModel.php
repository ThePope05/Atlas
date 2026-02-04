<?php

namespace Modules\ModuleLogin\Models;

use Exception;
use Libraries\Classes\Mvc\Model;

class ModuleLoginModel extends Model
{
    protected string $table = "Users";

    protected array $fillable = [
        "email",
        "password"
    ];

    public function Login(string $email, string $password): bool
    {
        try {
            $this->db->query("SELECT email, password FROM `$this->table` WHERE email = :email");
            $this->db->bind(":email", $email);
            $user = $this->db->first();

            if (!isset($user) || is_null($user))
                return false;

            return hash('sha256', $password) == $user->password;
        } catch (Exception $e) {
            throw new Exception("Error Processing Request $e", 1);
        }

        return false;
    }

    public function Register(string $email, string $password): bool
    {
        try {
            $user = [
                "email" => $email,
                "password" => hash('sha256', $password)
            ];
            $this->insert($user);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
