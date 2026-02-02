<?php

namespace Libraries\Classes\FileCompiler;

use Exception;

class FluxCompileEngine extends CompileEngine
{
    public function TryGetFile(string $filename, array $data = [])
    {
        $source = $this->getReadPath($filename);

        $compiled = $this->cachePath . md5($source) . '.php';

        if (!file_exists($compiled) || filemtime($source) > filemtime($compiled)) {
            $this->compile($source, $compiled);
        }

        extract($data, EXTR_SKIP);

        try {
            include $compiled;
        } catch (\Throwable $e) {
            throw new Exception("Error rendering view [$filename]", 0, $e);
        }
    }

    protected string $componentEngine = ComponentEngine::class;

    protected function compile(string $source, string $compiled)
    {
        $contents = file_get_contents($source);

        $contents = preg_replace(
            '/{{\s*(.+?)\s*}}/',
            '<?= $1 ?>',
            $contents
        );

        $contents = preg_replace_callback(
            '/@component\(\s*([\'"])([^\'"]+)\1(?:\s*,\s*([\'"])([^\'"]+)\3)?\s*\)/',
            fn($m) => "<?php"
                . (($m[4] == '') ? "
                \$componentEngineInstance = new \$this->componentEngine();
                \$componentEngineInstance->TryGetFile('{$m[2]}', get_defined_vars()); "
                    : "
                \$componentEngineInstance = new \$this->componentEngine();
                \$componentEngineInstance->ModuleName = '{$m[4]}';
                \$componentEngineInstance->TryGetFile('{$m[2]}', get_defined_vars());") . "?>",
            $contents
        );

        file_put_contents($compiled, $contents);
    }
}
