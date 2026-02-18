<?php

namespace Libraries\Classes\FileCompiler;

use Exception;

class FluxCompileEngine extends CompileEngine
{
    public function TryGetFile(string $filename, array $data = [])
    {
        if (!is_dir(__DIR__ . "/../../../public/cache"))
            mkdir(__DIR__ . "/../../../public/cache");

        if (!is_dir(__DIR__ . "/../../../public/cache/compiled"))
            mkdir(__DIR__ . "/../../../public/cache/compiled");

        $source = $this->getReadPath($filename);

        $compiled = $this->cachePath . md5($source) . '.php';

        if (!file_exists($compiled) || filemtime($source) > filemtime($compiled)) {
            $this->compile($source, $compiled);
        }

        // Render in an isolated scope so only $data keys become local variables.
        // Using a closure prevents caller variables from leaking into the template.
        $__render = static function (string $__file, array $__data): void {
            foreach ($__data as $__key => $__value) {
                $$__key = $__value;
            }
            unset($__key, $__value, $__data);
            include $__file;
        };

        try {
            $__render($compiled, $data);
        } catch (\Throwable $e) {
            throw new Exception("Error rendering view [$filename]", 0, $e);
        }
    }

    protected function compile(string $source, string $compiled)
    {
        $contents = file_get_contents($source);

        $contents = preg_replace_callback(
            '/{{\s*(.+?)\s*}}/',
            function ($m) {
                $expr = rtrim(trim($m[1]), ';');
                return "<?= htmlspecialchars($expr, ENT_QUOTES, 'UTF-8') ?>";
            },
            $contents
        );


        $contents = preg_replace_callback(
            '/@component\(\s*([\'"])([^\'"]+)\1(?:\s*,\s*([\'"])([^\'"]+)\3)?\s*\)/',
            fn($m) => "<?php"
                . ((!isset($m[4]) || $m[4] == '') ? "
                \$componentEngineInstance = new Libraries\Classes\FileCompiler\ComponentEngine();
                \$componentEngineInstance->TryGetFile('{$m[2]}', get_defined_vars()); "
                    : "
                \$componentEngineInstance = new Libraries\Classes\FileCompiler\ModuleComponentEngine();
                \$componentEngineInstance->ModuleName = '{$m[4]}';
                \$componentEngineInstance->TryGetFile('{$m[2]}', get_defined_vars());") .
                "\n ?>",
            $contents
        );

        $contents = preg_replace(
            '/@foreach\s*\((.+?)\)/',
            '<?php foreach ($1): ?>',
            $contents
        );

        $contents = preg_replace(
            '/@endforeach/',
            '<?php endforeach; ?>',
            $contents
        );

        $contents = preg_replace(
            '/@for\s*\((.+?)\)/',
            '<?php for ($1): ?>',
            $contents
        );

        $contents = preg_replace(
            '/@endfor/',
            '<?php endfor; ?>',
            $contents
        );

        $contents = preg_replace(
            '/@if\s*\((.*)\)/',
            '<?php if ($1): ?>',
            $contents
        );

        $contents = preg_replace(
            '/@endif/',
            '<?php endif; ?>',
            $contents
        );

        file_put_contents($compiled, $contents);
    }
}
