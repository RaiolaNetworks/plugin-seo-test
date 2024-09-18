<?php

declare(strict_types=1);

namespace Raiolanetworks\PluginSEOTest\Tests;

use PHPUnit\Framework\TestCase;
use Raiolanetworks\PluginSEOTest\Parser\HTMLParser;
use Raiolanetworks\PluginSEOTest\SEOData;

class SEODataTest extends TestCase
{
    public function test_it_parses_html_into_null_instance(): void
    {
        // Arrange
        $page = <<<'EMPTY_HTML'
<!DOCTYPE html>
<html>
    <head></head>
    <body></body>
</html>
EMPTY_HTML;

        // Act
        $parser = new HTMLParser($page);
        $seo    = new SEOData($parser);

        // Assert
        $this->assertNull($seo->title());
        $this->assertNull($seo->description());
        $this->assertNull($seo->image());
        $this->assertTrue($seo->robots()->isEmpty());
        $this->assertNull($seo->canonical());
        $this->assertNull($seo->prev());
        $this->assertNull($seo->next());
        $this->assertEmpty($seo->openGraph()->toArray());
        $this->assertEmpty($seo->twitter()->toArray());
        $this->assertEmpty($seo->alternateHrefLang()->jsonSerialize());
        $this->assertEmpty($seo->images());
        $this->assertEmpty($seo->h1s());
        $this->assertEmpty($seo->h2s());
        $this->assertNull($seo->charset());
    }

    public function test_it_parses_html_into_instance(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__ . '/stubs/test.html');

        // Act
        $parser = new HTMLParser($page);
        $seo    = new SEOData($parser);

        // Assert
        $this->assertEquals('This is my test title.', $seo->title());
        $this->assertEquals('This is the description of the test page.', $seo->description());
        $this->assertEquals('https://testpage.com/image.jpg', $seo->image());
        $this->assertEquals('follow, index', (string) $seo->robots());
        $this->assertEquals('https://testpage.com/example.html', $seo->canonical());
        $this->assertEquals('https://testpage.com/example.html?page=1', $seo->prev());
        $this->assertEquals('https://testpage.com/example.html?page=3', $seo->next());
        $this->assertEquals('OGFooBar', $seo->openGraph()->get('site_name'));
        $this->assertEquals('TwitterFooBar', $seo->twitter()->get('card'));
        $this->assertEquals([
            ['hreflang' => 'es', 'href' => 'https://testpage.com/es/example.html'],
            ['hreflang' => 'pt', 'href' => 'https://testpage.com/pt/example.html'],
        ], $seo->alternateHrefLang()->jsonSerialize());
        $this->assertEquals([
            ['src' => 'test-image.jpg', 'alt' => 'My alt text', 'title' => null],
        ], $seo->images());
        $this->assertEquals(['Header example'], $seo->h1s());
        $this->assertEquals(['Header 2 example', 'Header 2 example 2'], $seo->h2s());
        $this->assertEquals('utf-8', $seo->charset());
        //        $this->assertEquals('', $seo->ampHtmlLink());
    }
}
