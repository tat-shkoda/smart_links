<?php
declare(strict_types=1);

namespace App\Application\Validation;

use App\Application\Dto\CreateLinkInput;
use App\Infrastructure\LinkRepositoryInterface;

final class CreateLinkInputValidator
{

    public function __construct(
        private RuleValidator $validator,
        private LinkRepositoryInterface $repo,
    ) {}

    public function validate(CreateLinkInput $dto): void
    {
        if ($this->repo->exists($dto->code)) {
            throw new DuplicateCode("Link with code \"{$dto->code}\" already exists");
        }

        foreach ($dto->rules as $rule) {
            $this->validator->validate($rule['expression'] ?? '');
        }
    }
}
