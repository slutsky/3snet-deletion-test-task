<?php

declare(strict_types=1);

namespace Shared\Deletion\Service;

use Shared\Deletion\Dto\MetricsDto;

interface MetricsWatcher
{
    public function start(): void;

    public function stop(): void;

    public function isStarted(): bool;

    public function getMetrics(): MetricsDto;
}
