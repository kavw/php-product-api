<?php

declare(strict_types=1);

use App\Defaults;
use App\HttpApp;
use function App\env;

require_once __DIR__ . '/../vendor/autoload.php';

(function () {

    $app = HttpApp::boot(
        isDebug: env(Defaults::ENV_APP_DEBUG_PARAM, fn($v) => (bool)$v)
    );
    $req = $app->createRequestFromGlobals();
    $app->handle($req);

})();




