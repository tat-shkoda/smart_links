<?php

namespace App\Application\Services;

use App\Application\Dto\CreateLinkInput;
use App\Domain\Link;

interface CreateLinkServiceInterface
{

    public function create(CreateLinkInput $dto): Link;
}
