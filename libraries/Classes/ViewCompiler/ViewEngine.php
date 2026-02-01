<?php

namespace Libraries\Classes\ViewCompiler;

use Exception;
use Libraries\Constants\Compilable;

class ViewEngine
{
    protected string $viewsPath = __DIR__ . '/../../../public/views/';
    protected string $componentsPath = __DIR__ . '/../../../public/components/';
    protected string $modulePath = __DIR__ . '/../../../modules/';
    protected string $cachePath = __DIR__ . '/../../../public/cache/compiled/';

    public function render(string $view, array $data = [], Compilable $compileType = Compilable::View, string $moduleName = "")
    {
        $source = "";
        switch ($compileType) {
            case Compilable::Component:
                $source = $this->componentsPath;
                break;
            case Compilable::ModuleView:
                $source = $this->modulePath . $moduleName . "/views/";
                break;
            case Compilable::ModuleComponent:
                $source = $this->modulePath . $moduleName . "/components/";
                break;
            default:
                $source = $this->viewsPath;
                break;
        }

        $source .= "$view.rtc.php";

        $compiled = $this->cachePath . md5($source) . '.php';

        if (!file_exists($compiled) || filemtime($source) > filemtime($compiled)) {
            $this->compile($source, $compiled);
        }

        extract($data, EXTR_SKIP);

        try {
            include $compiled;
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
            fn($m) => "<?php \$this->render('{$m[1]}', get_defined_vars(), false, true); ?>",
            $contents
        );

        file_put_contents($compiled, $contents);
    }
}
