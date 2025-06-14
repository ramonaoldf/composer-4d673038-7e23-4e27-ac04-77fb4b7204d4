<?php

namespace Laravel\BrowserKitTesting;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTestCaseLifecycle;
use PHPUnit\Framework\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use Concerns\ImpersonatesUsers,
        Concerns\InteractsWithAuthentication,
        Concerns\InteractsWithConsole,
        Concerns\InteractsWithContainer,
        Concerns\InteractsWithDatabase,
        Concerns\InteractsWithExceptionHandling,
        Concerns\InteractsWithSession,
        Concerns\MakesHttpRequests,
        InteractsWithTestCaseLifecycle;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        if (method_exists(Application::class, 'inferBasePath')) {
            $app = require Application::inferBasePath().'/bootstrap/app.php';

            $app->make(Kernel::class)->bootstrap();

            return $app;
        }

        throw new RuntimeException(
            'Unable to guess application base directory. Please use the [Tests\CreatesApplication] trait.',
        );
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();
    }

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    protected function refreshApplication()
    {
        putenv('APP_ENV=testing');

        $this->app = $this->createApplication();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tearDownTheTestEnvironment();
    }
}
