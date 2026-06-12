<?php

namespace App\Infrastructure;

use App\Domain\Link;
use App\Domain\LinkReaderInterface;

class CachedLinkReader implements LinkReaderInterface
{

    private \Memcached $memcached;

    public function __construct(
        private LinkReaderInterface $reader,
    ) {
        $this->memcached = new \Memcached();
        $this->memcached->addServer('memcached', 11211);
    }

    public function read(string $code): Link
    {
        $cached = $this->memcached->get($code);
        if ($cached !== false) {
            return $cached;
        }

        $link = $this->reader->read($code);
        $this->memcached->set($code, $link, 60);

        return $link;
    }
}
