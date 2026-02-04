<?php

namespace App\Models;

use Libraries\Classes\Mvc\Model;

class WelcomeModel extends Model
{
    protected string $table = "reservations";

    protected array $fillable = [
        "id",
        "amount_adults",
        "amount_children",
        "date_arrival",
        "date_departure"
    ];

    public function GetAll()
    {
        return $this->all();
    }
}
