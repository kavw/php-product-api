<?php

declare(strict_types=1);

namespace App;

interface Defaults
{
    public const SERIALIZATION_FORMAT = 'json';
    public const CURRENCY = 'APP';

    public const ENV_APP_DEBUG_PARAM = 'APP_DEBUG';

    public const ENV_LOG_NAME_PARAM = 'APP_LOG_NAME';
    public const ENV_LOG_LEVEL_PARAM = 'APP_LOG_LEVEL_PARAM';
    public const ENV_LOG_PATH_PARAM = 'APP_LOG_PATH';

    public const ENV_PAYMENT_GATEWAY_URL_PARAM = 'APP_PAYMENT_GATEWAY_URL';

    public const ENV_POSTGRES_HOST_PARAM = 'POSTGRES_HOST';
    public const ENV_POSTGRES_DB_PARAM = 'POSTGRES_DB';
    public const ENV_POSTGRES_USER_PARAM = 'POSTGRES_USER';
    public const ENV_POSTGRES_PASS_PARAM = 'POSTGRES_PASS';

    public const ORDER_PRODUCTS_MAX = 10;
}
