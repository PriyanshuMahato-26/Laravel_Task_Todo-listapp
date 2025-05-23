<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit03c64a24b4fa9bc532170b7ca1dfb566
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Princ\\Task\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Princ\\Task\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit03c64a24b4fa9bc532170b7ca1dfb566::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit03c64a24b4fa9bc532170b7ca1dfb566::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit03c64a24b4fa9bc532170b7ca1dfb566::$classMap;

        }, null, ClassLoader::class);
    }
}
