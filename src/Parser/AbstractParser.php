<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\Parser;

/**
 * Class AbstractParser
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * Return the value of the given key name at the current line
     *
     * @param string $line
     * @param string $keyName
     * @return string
     */
    abstract public function getKey(string $line, string $keyName): string;

    /**
     * Return a md5 hash of the line
     *
     * @param string $line
     * @return string
     */
    public function getHash(string $line): string
    {
        return md5($line);
    }
}
