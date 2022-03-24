<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb1062924f7e8e36f4442eee0944f4b00
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mobili\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mobili\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/classes',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Detection' => 
            array (
                0 => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/namespaced',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Mobile_Detect' => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/Mobile_Detect.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb1062924f7e8e36f4442eee0944f4b00::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb1062924f7e8e36f4442eee0944f4b00::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb1062924f7e8e36f4442eee0944f4b00::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb1062924f7e8e36f4442eee0944f4b00::$classMap;

        }, null, ClassLoader::class);
    }
}