<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Form\Extension\Core\Type\TextType;
use HellCatIT\Twig\TwigStandalone;

define('ROOT', realpath(__DIR__));

$twigStandalone = TwigStandalone::getInstance();

// Create our first form!
$form = $twigStandalone->getFormFactory()->createBuilder()
    ->add('task', TextType::class)
    ->getForm();

var_dump($twigStandalone->getTwig()->render('index.html.twig', array(
    'form' => $form->createView(),
)));