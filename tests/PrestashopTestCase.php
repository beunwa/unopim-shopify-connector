<?php

namespace Webkul\Prestashop\Tests;

use Tests\TestCase;
use Webkul\User\Tests\Concerns\UserAssertions;
use Webkul\Prestashop\Models\PrestashopCredentialsConfig;

class PrestashopTestCase extends TestCase
{
    use UserAssertions;

    protected function setUp(): void
    {
        parent::setUp();

        PrestashopCredentialsConfig::factory()->create();
    }
}
