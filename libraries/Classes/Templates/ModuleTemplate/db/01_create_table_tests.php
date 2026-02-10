<?php

use Libraries\Classes\Database\SchemaBuilder;

return new class {

    public function Up(SchemaBuilder $schema): void
    {
        $schema->create("tests", [
            "id" => [
                "type" => "int",
                "unsigned",
                'auto_increment',
                'primary',
            ],
            "name" => [
                "type" => "varchar",
                "length" => 255,
                "not_null",
                "unique",
            ],
            "description" => [
                "type" => "varchar",
                "length" => 255,
                "not_null",
            ]
        ]);
    }

    public function Down(SchemaBuilder $schema)
    {
        $schema->drop("tests");
    }
};
