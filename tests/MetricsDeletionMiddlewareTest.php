<?php

declare(strict_types=1);

namespace Shared\Deletion\Tests;

use PHPUnit\Framework\TestCase;
use Shared\Deletion\Dto\MetricsDto;
use Shared\Deletion\Middleware\MetricsDeletionMiddleware;
use stdClass;

class MetricsDeletionMiddlewareTest extends TestCase
{
    private const METRIC_RELATIONS_DETATCH = 'relationsDetatch';
    private const METRIC_RELATIONS_DETATCHED = 'relationsDetatched';
    private const METRIC_CHILDREN_DELETE = 'childrenDelete';
    private const METRIC_CHILDREN_DELETED = 'childrenDeleted';
    private const METRIC_ROOT_DELETE = 'rootDelete';
    private const METRIC_ROOT_DELETED = 'rootDeleted';
    private const ROOT_CLASS = 'RootClass';
    private const PARENT_CLASS = 'ParentClass';
    private const CHILD_CLASS = 'ChildClass';

    public function testCreation(): void
    {
        $middleware = new MetricsDeletionMiddleware();

        self::assertFalse($middleware->isStarted());
        self::assertMetricsEmpty($middleware->getMetrics());
    }

    public function testSupports(): void
    {
        $middleware = new MetricsDeletionMiddleware();

        self::assertTrue($middleware->supports(self::ROOT_CLASS));
    }

    public function testStart(): void
    {
        $middleware = new MetricsDeletionMiddleware();

        $middleware->start();

        self::assertTrue($middleware->isStarted());
    }

    public function testStop(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->stop();

        self::assertFalse($middleware->isStarted());
        self::assertMetricsEmpty($middleware->getMetrics());
    }

    public function testReset(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();
        $middleware->beforeDetachRelations(self::PARENT_CLASS, self::CHILD_CLASS, [], [], new stdClass());

        $middleware->reset();

        self::assertFalse($middleware->isStarted());
        $metrics = $middleware->getMetrics();
        self::assertMetricsEmpty($metrics);
    }

    public function testBeforeDetachRelations(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->beforeDetachRelations(self::PARENT_CLASS, self::CHILD_CLASS, [], [], new stdClass());

        $metrics = $middleware->getMetrics();
        self::assertMetricsEmptyExclude($metrics, self::METRIC_RELATIONS_DETATCH);
        self::assertEquals(1, $metrics->relationsDetatch);
    }

    public function testAfterDetachRelations(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->afterDetachRelations(self::PARENT_CLASS, self::CHILD_CLASS, [], [], new stdClass());

        $metrics = $middleware->getMetrics();
        self::assertMetricsEmptyExclude($metrics, self::METRIC_RELATIONS_DETATCHED);
        self::assertEquals(1, $metrics->relationsDetatched);
    }

    public function testBeforeDeleteChildren(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->beforeDeleteChildren(self::CHILD_CLASS, [], new stdClass());

        $metrics = $middleware->getMetrics();
        self::assertMetricsEmptyExclude($metrics, self::METRIC_CHILDREN_DELETE);
        self::assertEquals(1, $metrics->childrenDelete);
    }

    public function testAfterDeleteChildren(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->afterDeleteChildren(self::CHILD_CLASS, [], new stdClass());

        $metrics = $middleware->getMetrics();
        self::assertMetricsEmptyExclude($metrics, self::METRIC_CHILDREN_DELETED);
        self::assertEquals(1, $metrics->childrenDeleted);
    }

    public function testBeforeDeleteRoot(): void
    {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->beforeDeleteRoot(new stdClass());

        $metrics = $middleware->getMetrics();
        self::assertMetricsEmptyExclude($metrics, self::METRIC_ROOT_DELETE);
        self::assertEquals(1, $metrics->rootDelete);
    }

    public function testAfterDeleteRoot(): void {
        $middleware = new MetricsDeletionMiddleware();
        $middleware->start();

        $middleware->afterDeleteRoot(new stdClass());

        $metrics = $middleware->getMetrics();
        self::assertMetricsEmptyExclude($metrics, self::METRIC_ROOT_DELETED);
        self::assertEquals(1, $metrics->rootDeleted);
    }

    public static function assertMetricsEmpty(MetricsDto $metrics): void
    {
        self::assertMetricsEmptyExclude($metrics, null);
    }

    /**
     * @param self::METRIC_*|null $excludeMetric
     */
    public static function assertMetricsEmptyExclude(MetricsDto $metrics, ?string $excludeMetric = null): void
    {
        if (self::METRIC_RELATIONS_DETATCH !== $excludeMetric) {
            self::assertEquals(0, $metrics->relationsDetatch, 'The "relationsDetatch" metric must be empty.');
        }

        if (self::METRIC_RELATIONS_DETATCHED !== $excludeMetric) {
            self::assertEquals(0, $metrics->relationsDetatched, 'The "relationsDetatched" metric must be empty.');
        }

        if (self::METRIC_CHILDREN_DELETE !== $excludeMetric) {
            self::assertEquals(0, $metrics->childrenDelete, 'The "childrenDelete" metric must be empty.');
        }

        if (self::METRIC_CHILDREN_DELETED !== $excludeMetric) {
            self::assertEquals(0, $metrics->childrenDeleted, 'The "childrenDeleted" metric must be empty.');
        }

        if (self::METRIC_ROOT_DELETE !== $excludeMetric) {
            self::assertEquals(0, $metrics->rootDelete, 'The "rootDelete" metric must be empty.');
        }

        if (self::METRIC_ROOT_DELETED !== $excludeMetric) {
            self::assertEquals(0, $metrics->rootDeleted, 'The "rootDeleted" metric must be empty.');
        }
    }
}
