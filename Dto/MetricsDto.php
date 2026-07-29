<?php

declare(strict_types=1);

namespace Shared\Deletion\Dto;

final readonly class MetricsDto
{
    public function __construct(
        public readonly int $relationsDetatch = 0,
        public readonly int $relationsDetatched = 0,
        public readonly int $childrenDelete = 0,
        public readonly int $childrenDeleted = 0,
        public readonly int $rootDelete = 0,
        public readonly int $rootDeleted = 0,
    ) {
    }
}
