<?php
namespace core\utils;
class Directories
{
	public static function makeDirsRecusive($dir)
    {
        $dirs = explode('/' , $dir);
        $path = rtrim(DIR, '/');
        $oldMask = umask(0);
        for($i = 1; $i < count($dirs); ++$i) {
            $path .= '/' . $dirs[$i];
            if (!(file_exists($path) && is_dir($path))) {
                if(!mkdir($path, 0777)) {
                     throw new \Exception('In class ' . get_class() . ' cannot create directory ' . $path);
                }
            }
        }
        umask($oldMask);
    }
}