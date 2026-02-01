<?php

namespace Libraries\Classes\ViewCompiler;

use Exception;

class ViewEngine
{
    protected string $viewsPath = __DIR__ . '/../../../public/views';
    protected string $cachePath = __DIR__ . '/../../../public/cache/views';

    public function render(string $view, array $data = [], bool $isComponent = false)
    {
        $source = $this->viewsPath . (($isComponent) ? "/../components" : "") . "/$view.rtc.php";
        $compiled = $this->cachePath . (($isComponent) ? "/../components" : "") . '/' . md5($source) . '.php';

        if (!file_exists($compiled) || filemtime($source) > filemtime($compiled)) {
            $this->compile($source, $compiled);
        }

        extract($data, EXTR_SKIP);

        try {
            require $compiled;
        } catch (\Throwable $e) {
            throw new Exception("Error rendering view [$view]", 0, $e);
        }
    }

    protected function compile(string $source, string $compiled)
    {
        $contents = file_get_contents($source);

        $contents = preg_replace(
            '/{{\s*(.+?)\s*}}/',
            '<?= $1 ?>',
            $contents
        );

        $contents = preg_replace_callback(
            '/@component\(\s*[\'"](.+?)[\'"]\s*\)/',
            fn($m) => "<?php \$this->render('{$m[1]}', get_defined_vars(), true); ?>",
            $contents
        );

        file_put_contents($compiled, $contents);
    }
}
