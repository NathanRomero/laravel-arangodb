<?php

declare(strict_types=1);

namespace Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Console\Application as Artisan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\ParallelTesting;
use LaravelFreelancerNL\Aranguent\Testing\DatabaseTransactions;
use LaravelFreelancerNL\Aranguent\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\Concerns\HandlesAnnotations;
use Orchestra\Testbench\Concerns\HandlesDatabases;
use Orchestra\Testbench\Concerns\HandlesRoutes;
use Orchestra\Testbench\Concerns\WithFactories;
use Orchestra\Testbench\Concerns\WithLaravelMigrations;
use Orchestra\Testbench\Concerns\WithLoadMigrationsFrom;
use PHPUnit\Framework\TestCase;
use Throwable;

trait OrchestraTestbenchTesting
{
    use CreatesApplication;
    use HandlesAnnotations;
    use HandlesDatabases;
    use HandlesRoutes;
    use WithLaravelMigrations;
    use WithLoadMigrationsFrom;
    use WithFaker;

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The callbacks that should be run after the application is created.
     *
     * @var array
     */
    protected $afterApplicationCreatedCallbacks = [];

    /**
     * The callbacks that should be run after the application is refreshed.
     *
     * @var array
     */
    protected $afterApplicationRefreshedCallbacks = [];

    /**
     * The callbacks that should be run before the application is destroyed.
     *
     * @var array
     */
    protected $beforeApplicationDestroyedCallbacks = [];

    /**
     * The exception thrown while running an application destruction callback.
     *
     * @var \Throwable
     */
    protected $callbackException;

    /**
     * Indicates if we have made it through the base setUp function.
     *
     * @var bool
     */
    protected $setUpHasRun = false;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    final protected function setUpTheTestEnvironment(): void
    {
        if (! $this->app) {
            $this->refreshApplication();

            $this->setUpParallelTestingCallbacks();
        }

        foreach ($this->afterApplicationRefreshedCallbacks as $callback) {
            \call_user_func($callback);
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            \call_user_func($callback);
        }

        Model::setEventDispatcher($this->app['events']);

        $this->setUpApplicationRoutes();

        $this->setUpHasRun = true;
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    final protected function tearDownTheTestEnvironment(): void
    {
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();

            $this->tearDownParallelTestingCallbacks();

            $this->app->flush();

            $this->app = null;
        }

        $this->setUpHasRun = false;

        if (property_exists($this, 'serverVariables')) {
            $this->serverVariables = [];
        }

        if (property_exists($this, 'defaultHeaders')) {
            $this->defaultHeaders = [];
        }

        if (class_exists(Mockery::class)) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }

        Carbon::setTestNow();

        if (class_exists(CarbonImmutable::class)) {
            CarbonImmutable::setTestNow();
        }

        $this->afterApplicationCreatedCallbacks = [];
        $this->beforeApplicationDestroyedCallbacks = [];

        Artisan::forgetBootstrappers();

        Queue::createPayloadUsing(null);

        if ($this->callbackException) {
            throw $this->callbackException;
        }
    }

    /**
     * Boot the testing helper traits.
     *
     * @param  array  $uses
     *
     * @return array
     */
    final protected function setUpTheTestEnvironmentTraits(array $uses): array
    {
        $this->setUpDatabaseRequirements(function () use ($uses) {
            if (isset($uses[RefreshDatabase::class])) {
                $this->refreshDatabase();
            }

            if (isset($uses[DatabaseMigrations::class])) {
                $this->runDatabaseMigrations();
            }
        });

        if (isset($uses[DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction();
        }

        if (isset($uses[WithoutMiddleware::class])) {
            $this->disableMiddlewareForAllTests();
        }

        if (isset($uses[WithoutEvents::class])) {
            $this->disableEventsForAllTests();
        }

        if (isset($uses[WithFaker::class])) {
            $this->setUpFaker();
        }

        return $uses;
    }

    /**
     * Setup parallel testing callback.
     */
    protected function setUpParallelTestingCallbacks(): void
    {
        if (class_exists(ParallelTesting::class) && $this instanceof TestCase) {
            ParallelTesting::callSetUpTestCaseCallbacks($this);
        }
    }

    /**
     * Teardown parallel testing callback.
     */
    protected function tearDownParallelTestingCallbacks(): void
    {
        if (class_exists(ParallelTesting::class) && $this instanceof TestCase) {
            ParallelTesting::callTearDownTestCaseCallbacks($this);
        }
    }

    /**
     * Register a callback to be run after the application is refreshed.
     *
     * @param  callable  $callback
     *
     * @return void
     */
    protected function afterApplicationRefreshed(callable $callback): void
    {
        $this->afterApplicationRefreshedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            \call_user_func($callback);
        }
    }

    /**
     * Register a callback to be run after the application is created.
     *
     * @param  callable  $callback
     *
     * @return void
     */
    public function afterApplicationCreated(callable $callback): void
    {
        $this->afterApplicationCreatedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            \call_user_func($callback);
        }
    }

    /**
     * Register a callback to be run before the application is destroyed.
     *
     * @param  callable  $callback
     *
     * @return void
     */
    protected function beforeApplicationDestroyed(callable $callback): void
    {
        array_unshift($this->beforeApplicationDestroyedCallbacks, $callback);
    }

    /**
     * Execute the application's pre-destruction callbacks.
     *
     * @return void
     */
    protected function callBeforeApplicationDestroyedCallbacks()
    {
        foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
            try {
                \call_user_func($callback);
            } catch (Throwable $e) {
                if (! $this->callbackException) {
                    $this->callbackException = $e;
                }
            }
        }
    }

    /**
     * Reload the application instance with cached routes.
     */
    protected function reloadApplication(): void
    {
        $this->tearDownTheTestEnvironment();
        $this->setUpTheTestEnvironment();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    abstract protected function setUpTraits();

    /**
     * Refresh the application instance.
     *
     * @return void
     */
    abstract protected function refreshApplication();
}
