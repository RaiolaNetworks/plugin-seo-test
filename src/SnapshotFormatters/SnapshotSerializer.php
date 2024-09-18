<?php

declare(strict_types=1);

namespace Raiolanetworks\PluginSEOTest\SnapshotFormatters;

use Raiolanetworks\PluginSEOTest\SEOData;

interface SnapshotSerializer
{
    public function toArray(SEOData $data): array;
}
