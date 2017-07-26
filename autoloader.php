<?php
class Autoloader
{
    public static function loader($class)
    {
        $dir = realpath(__DIR__ . '/src');
        $filename = $class . '.php';
        $file = $dir . '/' . $filename;
        if (!file_exists($file)) {
            return false;
        }
        include $file;
    }
}
