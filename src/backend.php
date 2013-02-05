<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Controladores relacionados con la parte de administración del sitio web
$backend = $app['controllers_factory'];

// Protección extra que asegura que al backend sólo acceden los administradores
$backend->before(function () use($app) {
    if (!$app['security']->isGranted('ROLE_ADMIN')) {
        return new RedirectResponse($app['url_generator']->generate('homepage'));
    }
});

// MAIN -----------------------------------------------------------------
$backend->get('/', function () use ($app) {
    $entries = $app['db']->fetchAll("SELECT * FROM entry");

    return $app['twig']->render('backend/main.twig', array(
        'entries' => $entries
    ));
})
->bind('backend');

// ADD -----------------------------------------------------------------
$backend->match('/entry/new', function(Request $request) use($app) {

	$form = $app['form.factory']->createBuilder('form')
		->add('title', 'text', array(
			'label' => 'Título',
			'required' => true,
			'max_length' => 255,
			'attr' => array(
				'class' => 'span8',
			)
		))
		->add('content', 'textarea', array(
			'label' => 'Contenido',
			'required' => false,
			'max_length' => 2000,
			'attr' => array(
				'class' => 'span8',
				'rows' => '10',
			)
		))
		->getForm();

	if ('POST' == $request->getMethod()) {
		$form->bind($request);

		if ($form->isValid()) {
			$entry = $form->getData();
			$entry['created_at'] = date("Y-m-d H:i:s");
			$app['db']->insert('entry', $entry);

			return new RedirectResponse($app['url_generator']->generate('backend'));
		}
	}

    return $app['twig']->render('backend/entry/new.twig', array('form' => $form->createView()));

})
->bind('entry_new');

// EDIT -----------------------------------------------------------------
$backend->match('/entry/{id}/edit', function(Request $request, $id) use($app) {

	$entry = $app['db']->fetchAssoc('SELECT * FROM entry WHERE id = ?', array($id));

	if (!$entry) {
		return new RedirectResponse($app['url_generator']->generate('backend'));
	}

	$form = $app['form.factory']->createBuilder('form', $entry)
		->add('title', 'text', array(
			'label' => 'Título',
			'required' => true,
			'max_length' => 255,
			'attr' => array(
				'class' => 'span8',
			)
		))
		->add('content', 'textarea', array(
			'label' => 'Contenido',
			'required' => false,
			'max_length' => 2000,
			'attr' => array(
				'class' => 'span8',
				'rows' => '10',
			)
		))
		->add('created_at', 'text', array(
			'label' => 'Fecha creación',
			'read_only' => true,
			'attr' => array(
				'class' => 'span8',
			)
		))
		->getForm();

		if ('POST' == $request->getMethod()) {
			$form->bind($request);

			if ($form->isValid()) {
				$entry = $form->getData();
				
				$app['db']->update('entry', 
								array('title' => $entry['title'], 'content' => $entry['content']),
								array('id' => $id)
				);

				return new RedirectResponse($app['url_generator']->generate('backend'));
			}
		}

    return $app['twig']->render('backend/entry/edit.twig', array('entry' => $entry, 'form' => $form->createView()));
})
->bind('entry_edit');



// DELETE -----------------------------------------------------------------
$backend->match('/entry/{id}/delete', function($id) use($app) {
	$entry = $app['db']->delete('entry', array('id' => $id));

    return new RedirectResponse($app['url_generator']->generate('backend'));	
})
->bind('entry_delete');


return $backend;
