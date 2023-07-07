<?php

declare(strict_types=1);

use App\Defaults;
use App\HttpApp;
use Spiral\RoadRunner;
use Nyholm\Psr7;
use function App\env;

include __DIR__ ."/../../vendor/autoload.php";

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr7\Factory\Psr17Factory();

$psr7 = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

$app = HttpApp::boot(
    isDebug: env(Defaults::ENV_APP_DEBUG_PARAM, fn($v) => (bool)$v)
);



while (true) {
    try {
        $request = $psr7->waitRequest();

        if (!($request instanceof \Psr\Http\Message\ServerRequestInterface)) {
            break;
        }
    } catch (\Throwable) {
        $psr7->respond(new Psr7\Response(400));
        continue;
    }

    try {
        $res = $app->getResponse($request);
        $psr7->respond($res);
    } catch (\Throwable) {
        $psr7->respond(new Psr7\Response(500, [], 'Something Went Wrong!'));
    }
}
