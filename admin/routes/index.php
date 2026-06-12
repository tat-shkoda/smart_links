<?php

use App\Application\Actions\CreateLinkAction;
use App\Application\Actions\GetLinkByCodeAction;
use Slim\App;

return function (App $app) {
    $app->post('/links', CreateLinkAction::class);
    $app->get('/links/{code}', GetLinkByCodeAction::class);
};