<?php

namespace Libraries\Classes\Database;

use InvalidArgumentException;
use PDO;
use RuntimeException;
use Schema;

class SchemaEngine
{
    protected string $path = __DIR__ . "/../../../app/db/.schemas.json";

    public function RunUp(SchemaBuilder $schemaBuilder): void
    {
        foreach ($this->getPendingSchemas() as $file) {
            $schema = require $file;

            if (!method_exists($schema, 'up'))
                throw new RuntimeException("Schema {$file} has no up()");

            $schema->up($schemaBuilder);

            $this->markAsRun($file);
        }
    }

    // public function RunSchema(SchemaBuilder $schemaBuilder, string $file): void
    // {
    //     $schema = require __DIR__ . "/../../../app/db/$file" . "_*.php";

    //     if (!method_exists($schema, 'up'))
    //         throw new RuntimeException("Schema {$file} has no up()");

    //     $schema->up($schemaBuilder);

    //     $this->markAsRun($file);
    // }

    public function ClearRunSchemas(): void
    {
        file_put_contents(
            $this->path,
            json_encode([], JSON_PRETTY_PRINT)
        );
    }

    protected function getPendingSchemas(): array
    {
        $executedSchemas = $this->getExecutedSchemas($this->path);
        $allSchemas = glob('./app/db/*.php');

        return array_filter($allSchemas, function ($schema) use ($executedSchemas) {
            return !in_array($schema, $executedSchemas, true);
        });
    }

    protected function markAsRun(string $file)
    {
        $executedSchemas = $this->getExecutedSchemas($this->path);
        $executedSchemas[] = $file;

        file_put_contents(
            $this->path,
            json_encode(array_values(array_unique($executedSchemas)), JSON_PRETTY_PRINT)
        );
    }

    protected function getExecutedSchemas()
    {
        if (!file_exists($this->path))
            return [];

        $content = file_get_contents($this->path);
        return json_decode($content, true) ?? [];
    }
}
