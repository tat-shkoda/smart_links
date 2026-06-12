<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Dto\CreateLinkInput;
use App\Application\Validation\CreateLinkInputValidator;
use App\Domain\Link;
use App\Domain\Rule;
use App\Infrastructure\LinkRepositoryInterface;

class CreateLinkService implements CreateLinkServiceInterface
{

    public function __construct(
        private CreateLinkInputValidator $validator,
        private LinkRepositoryInterface $repo,
    ) {}

    public function create(CreateLinkInput $dto): Link
    {
        $this->validator->validate($dto);

        $rules = array_map(
            fn(array $r) => new Rule(
                expression: $r['expression'],
                target: $r['target'],
                priority: (int) ($r['priority'] ?? 0),
            ),
            $dto->rules,
        );

        $link = new Link(
            code: $dto->code,
            default: $dto->default,
            rules: $rules,
        );

        $this->repo->create($link);

        return $link;
    }
}
