<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\Parser;

use function Safe\json_decode;

/**
 * Class JsonParser
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
class JsonParser extends AbstractParser
{
    /**
     * Return the value of the given key name at the current line
     *
     * @param string $line
     * @param string $keyName
     * @return string
     * @throws \Safe\Exceptions\JsonException
     */
    public function getKey(string $line, string $keyName): string
    {
        $item = json_decode($line, true);
        if (!isset($item[$keyName])) {
            throw new KeyNotFoundException(sprintf('Key "%s" not found', $keyName));
        }
        return (string) $item[$keyName];
    }
}
