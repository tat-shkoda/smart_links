<?php

namespace App\Infrastructure\Context;

use App\Domain\ContextInterface;
use App\Domain\ContextProviderInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeviceProvider implements ContextProviderInterface
{

    public function provide(ContextInterface $context, ServerRequestInterface $request): void
    {
        $context->set(
            'device',
            $this->detectDevice($request->getHeaderLine('User-Agent'))
        );
    }

    private function detectDevice(string $userAgent): string
    {
        if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            return 'iOS';
        }

        if (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        }

        return 'Desktop';
    }
}
