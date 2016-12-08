<?php

use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;

require_once 'TwigRenderer_RuntimeLoader.php';

define('ROOT', realpath(__DIR__));
define('DEFAULT_FORM_THEME', 'bootstrap_3_layout.html.twig');

define('VENDOR_DIR', realpath(ROOT . '/../vendor'));
define('VENDOR_FORM_DIR', VENDOR_DIR . '/symfony/form');
define('VENDOR_VALIDATOR_DIR', VENDOR_DIR . '/symfony/validator');
define('VENDOR_TWIG_BRIDGE_DIR', VENDOR_DIR . '/symfony/twig-bridge');
define('VIEWS_DIR', realpath(ROOT . '/../views'));

// Set up the CSRF Token Manager
$csrfTokenManager = new CsrfTokenManager();

// Set up the Validator component
$validator = Validation::createValidator();

// Set up the Translation component
$translator = new Translator('en');
$translator->addLoader('xlf', new XliffFileLoader());
$translator->addResource('xlf', VENDOR_FORM_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');
$translator->addResource('xlf', VENDOR_VALIDATOR_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');

$twig = new Twig_Environment(new Twig_Loader_Filesystem(array(
    VIEWS_DIR,
    VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form',
)), ['debug' => true, 'strict_variables' => true]);

$twig->addExtension(new TranslationExtension($translator));
$twig->addExtension(new FormExtension());

$twig->addRuntimeLoader(new TwigRenderer_RuntimeLoader([DEFAULT_FORM_THEME], $twig, $csrfTokenManager));

// Set up the Form component
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new CsrfExtension($csrfTokenManager))
    ->addExtension(new ValidatorExtension($validator))
    ->getFormFactory();
