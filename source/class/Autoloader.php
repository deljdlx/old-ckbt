<?php

namespace CKBT;

/** autoloader pour se passer de la magie de composer ; mode roots*/
class Autoloader
{
    private $namespace;
    private $path;

    public function __construct($namespace, $path)
    {
        $this->namespace = $namespace;
        $this->path = $path;
    }

    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
        return $this;
    }


    private function autoload($className)
    {
        if (!$this->isClassName($className)) {
            throw new \Exception('Fake class name autoloading attempt. Check your security');
        }
        $cleanedClassName = preg_replace('`^\\\\`', '', $className);
        $cleanedClassName = str_replace($this->namespace, '', $cleanedClassName);
        $cleanedClassName = str_replace('\\', \DIRECTORY_SEPARATOR, $cleanedClassName);
        $classFilePath = $this->path . '/' . $cleanedClassName . '.php';
        if (is_file($classFilePath)) {
            include($classFilePath);
        }
    }

    private function isClassName($className)
    {
        if (preg_match('`[^A-z\\_0-9]`', $className)) {
            return false;
        }
        return true;
    }
}