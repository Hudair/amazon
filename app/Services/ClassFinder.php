<?php

namespace App\Services;

class ClassFinder
{
    /**
     * Return all class names under the given namespace
     */
    public static function getClassesInNamespace($namespace)
    {
        $files = scandir(self::getNamespaceDirectory($namespace));

        $classes = array_map(function ($file) use ($namespace) {
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        return array_filter($classes, function ($possibleClass) {
            return class_exists($possibleClass);
        });
    }

    private static function getNamespaceDirectory($namespace)
    {
        $composerNamespaces = self::getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(base_path() . DIRECTORY_SEPARATOR . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }

    private static function getDefinedNamespaces()
    {
        $composerConfig = json_decode(file_get_contents(base_path('composer.json')));

        return (array) $composerConfig->autoload->{'psr-4'};
    }
}
