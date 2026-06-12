<?php

use App\Application\Actions\RedirectAction;
use Slim\App;

return function (App $app) {
    $app->get('/{code}', RedirectAction::class);
};
