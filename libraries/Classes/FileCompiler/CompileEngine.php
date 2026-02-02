<?php

namespace Libraries\Classes\FileCompiler;

use Exception;

class CompileEngine
{
    protected string $cachePath = __DIR__ . "/../../../public/cache/compiled/";

    // abstract 

    public function TryGetFile(string $filename)
    {

        if (!file_exists($this->cachePath))
            mkdir($this->cachePath, 0777, true);

        $fullFilePath = $this->getReadPath($filename);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $compiled = $this->cachePath . md5($filename) . '.' . $extension;

        if (!file_exists($compiled) || filemtime($fullFilePath) > filemtime($compiled)) {
            $this->compile($fullFilePath, $compiled);
        }

        try {
            include $compiled;
        } catch (\Throwable $e) {
            throw new Exception("Error rendering view [$view]", 0, $e);
        }
    }

    protected function getReadPath($filename): string
    {
        return __DIR__ . "/../../../" . $filename;
    }

    protected function compile(string $source, string $compiled)
    {
        $contents = file_get_contents($source);

        file_put_contents($compiled, $contents);
    }
}
