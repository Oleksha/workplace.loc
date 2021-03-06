<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4664f1c8990f7ae762ee1b786e01df1b
{
    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'workplace\\' => 10,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'V' => 
        array (
            'Valitron\\' => 9,
        ),
        'R' => 
        array (
            'RedBeanPHP\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'workplace\\' => 
        array (
            0 => __DIR__ . '/..' . '/workplace/core',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Valitron\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/valitron/src/Valitron',
        ),
        'RedBeanPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/gabordemooij/redbean/RedBeanPHP',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4664f1c8990f7ae762ee1b786e01df1b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4664f1c8990f7ae762ee1b786e01df1b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4664f1c8990f7ae762ee1b786e01df1b::$classMap;

        }, null, ClassLoader::class);
    }
}
