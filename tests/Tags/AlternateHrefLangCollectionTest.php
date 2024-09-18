<?php

declare(strict_types=1);

namespace Raiolanetworks\PluginTestSEO\Tests\Tags;

use PHPUnit\Framework\TestCase;
use Raiolanetworks\PluginTestSEO\Tags\AlternateHrefLangCollection;

class AlternateHrefLangCollectionTest extends TestCase
{
    public function test_should_work(): void
    {
        // Arrange
        $items = [
            ['hreflang' => 'en-us', 'href' => 'https://testsite.com/en/example.html'],
            ['hreflang' => 'es', 'href' => 'https://testsite.com/es/example.html'],
            ['hreflang' => 'pt', 'href' => 'https://testsite.com/pt/example.html'],
        ];

        // Act
        $collection = new AlternateHrefLangCollection($items);

        // Assert
        $this->assertCount(3, $collection->getHreflangs());
        $this->assertEquals('https://testsite.com/pt/example.html', $collection->get('pt'));
        $this->assertTrue($collection->has('en-us'));
        $this->assertFalse($collection->has('en-gb'));
    }
}
