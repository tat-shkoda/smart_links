<?php

namespace App\Domain;

interface LinkReaderInterface
{

    public function read(string $code): Link;
}
