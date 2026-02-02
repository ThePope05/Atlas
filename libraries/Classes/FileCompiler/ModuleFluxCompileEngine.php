<?php

namespace Libraries\Classes\FileCompiler;

use Exception;

class ModuleFluxCompileEngine extends FluxCompileEngine
{
    public string $ModuleName = "";

    public string $componentEngine = ModuleComponentEngine::class;
}
