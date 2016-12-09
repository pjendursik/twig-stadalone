<?php

namespace HellCatIT\Twig;

use \Twig_Environment;
use \Twig_Loader_Filesystem;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\Form\FormFactory;


final class TwigStandaloneFactory
{

    private static $DEFAULT_FORM_THEME = 'bootstrap_3_layout.html.twig';

    private $twig = null;
    private $formFactory = null;

    public function __construct(Twig_Environment $twig, FormFactory $formFactory)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
    }

    /**
     * @return null|Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @return null|\Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public static function createTwigEnvironmentInstance()
    {
        $VENDOR_DIR = realpath(ROOT . '/../vendor');
        $VENDOR_FORM_DIR =  $VENDOR_DIR . '/symfony/form';
        $VENDOR_VALIDATOR_DIR = $VENDOR_DIR . '/symfony/validator';
        $VENDOR_TWIG_BRIDGE_DIR = $VENDOR_DIR . '/symfony/twig-bridge';
        $VIEWS_DIR = realpath(ROOT . '/../views');

        // Set up the CSRF Token Manager
        $csrfTokenManager = new CsrfTokenManager();

        // Set up the Translation component
        $translator = new Translator('en');
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource('xlf', $VENDOR_FORM_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');
        $translator->addResource('xlf', $VENDOR_VALIDATOR_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');

        $twig = new Twig_Environment(new Twig_Loader_Filesystem(array(
            $VIEWS_DIR,
            $VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form',
        )), ['debug' => true, 'strict_variables' => true]);

        $twig->addExtension(new TranslationExtension($translator));
        $twig->addExtension(new FormExtension());

        $twig->addRuntimeLoader(new TwigRenderer_RuntimeLoader([self::$DEFAULT_FORM_THEME], $twig, $csrfTokenManager));

        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new CsrfExtension($csrfTokenManager))
            ->addExtension(new ValidatorExtension(Validation::createValidator()))
            ->getFormFactory();

        return new self($twig, $formFactory);
    }
}