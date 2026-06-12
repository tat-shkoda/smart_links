<?php

namespace App\Infrastructure;

use App\Domain\Link;

interface LinkRepositoryInterface
{

    public function create(Link $link): void;
    public function getByCode(string $code): Link;
    public function exists(string $code): bool;
}
