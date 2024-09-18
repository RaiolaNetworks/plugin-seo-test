<?php

declare(strict_types=1);

namespace Raiolanetworks\PluginTestSEO\SnapshotFormatters;

use Raiolanetworks\PluginTestSEO\SEOData;

interface SnapshotSerializer
{
    public function toArray(SEOData $data): array;
}
