<?php

namespace HellCatIT\Twig;

use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class TwigRenderer_RuntimeLoader implements \Twig_RuntimeLoaderInterface
{

    private $defaultThemes = [];
    private $environment;
    private $csrfTokenManager = null;

    public function __construct(array $defaultThemes = array(), \Twig_Environment $environment = null, CsrfTokenManagerInterface $csrfTokenManager = null)
    {
        $this->defaultThemes = $defaultThemes;
        $this->environment = $environment;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function load($class)
    {
        if ($class === TwigRenderer::class) {
            return new TwigRenderer(
                new TwigRendererEngine($this->defaultThemes, $this->environment),
                $this->csrfTokenManager
            );
        }
        return null;
    }
}