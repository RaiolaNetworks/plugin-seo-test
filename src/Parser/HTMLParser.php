<?php

declare(strict_types=1);

namespace Raiolanetworks\PluginSEOTest\Parser;

use DOMElement;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

class HTMLParser
{
    private Crawler $crawler;

    public function __construct(string $html)
    {
        $this->crawler = new Crawler($html);
    }

    public function grabTextFrom(string $xpath): ?string
    {
        return $this->crawler->filterXPath($xpath)->text('') ?: null;
    }

    /**
     * @template T of string|array<string>
     *
     * @param  T  $attributes
     * @return (T is string ? string|null : array<string, string|null>|null)
     */
    public function grabAttributeFrom(string $xpath, string|array $attributes)
    {
        $nodes = $this->crawler->filterXPath($xpath);

        if ($nodes->count() === 0) {
            return null;
        }

        return $this->getArgumentsFromNode($nodes->getNode(0), $attributes);
    }

    /**
     * @param  string|array<string>|null  $attribute
     */
    public function grabMultiple(string $xpath, $attribute = null): array
    {
        $result = [];
        $nodes = $this->crawler->filterXPath($xpath);

        foreach ($nodes as $node) {
            $result[] = $attribute !== null ? $this->getArgumentsFromNode($node, $attribute) : $node->textContent;
        }

        return $result;
    }

    /**
     * @template T of string|array<string>
     *
     * @param  T  $attributes
     * @return (T is string ? string : array<string, string|null>)
     */
    private function getArgumentsFromNode(DOMElement|DOMNode|null $element, string|array $attributes)
    {
        if (! $element || ! ($element instanceof DOMElement)) {
            return [];
        }

        if (is_string($attributes)) {
            return $element->getAttribute($attributes);
        }

        $result = [];

        foreach ($attributes as $attribute) {
            $result[$attribute] = $element->getAttribute($attribute) ?: null;
        }

        return $result;
    }
}
