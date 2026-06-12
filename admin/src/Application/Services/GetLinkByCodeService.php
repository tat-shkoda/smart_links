<?php

namespace App\Application\Services;

use App\Domain\Link;
use App\Infrastructure\LinkRepositoryInterface;

class GetLinkByCodeService implements GetLinkByCodeServiceInterface
{

    public function __construct(
        private LinkRepositoryInterface $repo,
    ) {}

    public function get(string $code): Link
    {
        return $this->repo->getByCode($code);
    }
}
