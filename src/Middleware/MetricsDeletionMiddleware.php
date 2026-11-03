<?php

declare(strict_types=1);

namespace Shared\Deletion\Middleware;

use Shared\Deletion\Dto\MetricsDto;
use Shared\Deletion\Service\MetricsWatcher;
use Symfony\Contracts\Service\ResetInterface;

final class MetricsDeletionMiddleware implements MetricsWatcher, ResetInterface
{
    /**
     * @var array{
     *     relationsDetatch: int,
     *     relationsDetatched: int,
     *     childrenDelete: int,
     *     childrenDeleted: int,
     *     rootDelete: int,
     *     rootDeleted: int,
     * }|null
     */
    private ?array $metrics = null;

    private bool $started = false;

    public function reset(): void
    {
        $this->metrics = null;
        $this->started = false;
    }

    public function supports(string $entityClass): bool
    {
        return true;
    }

    public function beforeDetachRelations(
        string $parentClass,
        string $childClass,
        array $childIds,
        array $relation,
        object $root,
    ): void {
        if ($this->started) {
            $this->metrics['relationsDetatch']++;
        }
    }

    public function afterDetachRelations(
        string $parentClass,
        string $childClass,
        array $childIds,
        array $relation,
        object $root,
    ): void {
        if ($this->started) {
            $this->metrics['relationsDetatched']++;
        }
    }

    public function beforeDeleteChildren(
        string $childClass,
        array $childIds,
        object $root,
    ): void {
        if ($this->started) {
            $this->metrics['childrenDelete']++;
        }
    }

    public function afterDeleteChildren(
        string $childClass,
        array $childIds,
        object $root,
    ): void {
        if ($this->started) {
            $this->metrics['childrenDeleted']++;
        }
    }

    public function beforeDeleteRoot(object $root): void
    {
        if ($this->started) {
            $this->metrics['rootDelete']++;
        }
    }

    public function afterDeleteRoot(object $root): void
    {
        if ($this->started) {
            $this->metrics['rootDeleted']++;
        }
    }

    public function start(): void
    {
        if (null === $this->metrics) {
            $this->metrics = [
                'relationsDetatch' => 0,
                'relationsDetatched' => 0,
                'childrenDelete' => 0,
                'childrenDeleted' => 0,
                'rootDelete' => 0,
                'rootDeleted' => 0,
            ];
        }

        $this->started = true;
    }

    public function stop(): void
    {
        $this->started = false;
    }

    public function isStarted(): bool
    {
        return $this->started;
    }

    public function getMetrics(): MetricsDto
    {
        if (null === $this->metrics) {
            return new MetricsDto();
        }

        return new MetricsDto(...$this->metrics);
    }
}
