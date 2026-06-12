<?php

namespace Tests\Infrastructure;

use App\Domain\Link;
use App\Domain\LinkReaderInterface;
use App\Infrastructure\CachedLinkReader;
use Override;
use Tests\TestCase;

class CachedLinkReaderTest extends TestCase
{

    private \Memcached $cache;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->cache = new \Memcached();
        $this->cache->addServer('memcached', 11211);
        $this->cache->flush();
    }

    public function testWhenCached(): void
    {
        $code = uniqid();

        $link = new Link($code, 'https://example.com', []);
        $this->cache->set($code, $link, 60);

        $inner = $this->createMock(LinkReaderInterface::class);

        $reader = new CachedLinkReader($inner);
        $result = $reader->read($code);

        $this->assertEquals($link, $result);
    }

    public function testWhenNotCached(): void
    {
        $code = uniqid();

        $link = new Link($code, 'https://example.com', []);

        $inner = $this->createMock(LinkReaderInterface::class);
        $inner->method('read')->with($code)->willReturn($link);

        $reader = new CachedLinkReader($inner);
        $result = $reader->read($code);

        $this->assertEquals($link, $result);
    }
}
