<?php

namespace Libraries\Classes\ModuleLoader;

class ModuleEngine
{
    public function LoadModules()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../../../config/modules.json'), true);

        foreach ($config['modules'] as $data) {
            if (!$data['enabled']) continue;
            $name = $data["name"];

            $modulePath = "modules/$name";
            $moduleMeta = json_decode(file_get_contents(__DIR__ . "/../../../$modulePath/module.json"), true);

            $class = "\\Modules\\$name\\" . $moduleMeta['main'];

            $modules[] = $class;
        }

        foreach ($modules as $module) {
            $module::register();
        }
    }
}
