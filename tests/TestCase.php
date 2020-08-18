<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->_before();
    }

    public function tearDown(): void
    {
        $this->_after();

        parent::tearDown();
    }

    protected function _before(): void
    {
        // Override
    }

    protected function _after(): void
    {
        // Override
    }
}
