<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7a897eb6a1ca1c2cd5fa19a8f9b9f808
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit7a897eb6a1ca1c2cd5fa19a8f9b9f808::$classMap;

        }, null, ClassLoader::class);
    }
}