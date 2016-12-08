<?php

use Symfony\Component\Form\Extension\Core\Type\TextType;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/setup.php';

// Create our first form!
$form = $formFactory->createBuilder()
    ->add('task', TextType::class)
    ->getForm();

var_dump($twig->render('index.html.twig', array(
    'form' => $form->createView(),
)));