<?php

namespace Libraries\Classes\FileCompiler;

use Exception;

class ModuleFluxCompileEngine extends FluxCompileEngine
{
    public string $ModuleName = "";

    public function TryGetFile(string $filename, array $data = [])
    {
        if (!$this->isModuleOn())
            return null;

        return parent::TryGetFile($filename, $data);
    }

    private function isModuleOn(): bool
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../../../config/modules.json'), true);
        $result = false;

        foreach ($config as $data) {
            if (!$data['enabled']) continue;
            $result = $this->ModuleName == $data["name"];

            if ($result) break;
        }

        return $result;
    }
}
