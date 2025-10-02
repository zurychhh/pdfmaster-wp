<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FileHandlerTest extends TestCase
{
    public function test_placeholder(): void
    {
        $this->markTestSkipped('WordPress environment bootstrap required for processor tests.');
    }
}
