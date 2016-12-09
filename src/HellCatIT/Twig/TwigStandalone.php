<?php

namespace HellCatIT\Twig;

final class TwigStandalone
{
    private static $instance = null;

    private function __construct() { }

    public static function getInstance()
    {
        if(self::$instance === null) {
            self::$instance = TwigStandaloneFactory::createTwigEnvironmentInstance();
        }
        return self::$instance;
    }
}