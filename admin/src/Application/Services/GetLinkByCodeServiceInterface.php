<?php

namespace App\Application\Services;

use App\Domain\Link;

interface GetLinkByCodeServiceInterface
{

    public function get(string $code): Link;
}
