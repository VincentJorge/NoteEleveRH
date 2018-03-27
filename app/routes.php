<?php
use App\Controller;

$container = $app->getContainer();

// Routes
$app->group('/user', function() use ($app) {
    $app->get('', Controller\UsersController::class.':getAll');
    $app->get('/{userId}', Controller\UsersController::class.':getOne');
    $app->post('', Controller\UsersController::class.':addOne');
    $app->patch('/{userId}', Controller\UsersController::class.':updateOne');
    $app->delete('/{userId}', Controller\UsersController::class.':deleteOne');
    $app->get('/{userId}/skill', Controller\UsersController::class.':getSkillByUser');
    $app->get('/skill/{type}', Controller\UsersController::class.':getUserBySkillOrName');
    $app->get('/skill/{type}/{note}', Controller\UsersController::class.':getUserBySkillOrNote');
});

$app->group('/project', function() use($app) {
    $app->get('', Controller\ProjectsController::class.':getAll');
    $app->get('/{projectId}', Controller\ProjectsController::class.':getOne');
    $app->post('',Controller\ProjectsController::class.':addOne');
    $app->patch('/{projectId}', Controller\ProjectsController::class.':updateOne');
    $app->delete('/{projectId}', Controller\ProjectsController::class.':deleteOne');
});

$app->group('/skill', function() use($app) {
    $app->get('/{skillId}', Controller\SkillsController::class.':getOne');
    $app->post('',Controller\SkillsController::class.':addOne');
    $app->patch('/{skillId}', Controller\SkillsController::class.':updateOne');
    $app->delete('/{skillId}', Controller\SkillsController::class.':deleteOne');
});