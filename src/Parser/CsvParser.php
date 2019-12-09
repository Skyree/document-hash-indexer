<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\Parser;

/**
 * Class CsvParser
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
class CsvParser extends AbstractParser
{
    /**
     * Return the value of the given column at the current line
     *
     * @param string $line
     * @param string $keyName
     * @return string
     */
    public function getKey(string $line, string $keyName): string
    {
        $item = str_getcsv($line);
        if (!isset($item[$keyName])) {
            throw new KeyNotFoundException(sprintf('Key "%s" not found', $keyName));
        }
        return (string) $item[(int) $keyName];
    }
}
