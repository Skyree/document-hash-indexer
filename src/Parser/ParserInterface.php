<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\Parser;

/**
 * Class ParserInterface
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
interface ParserInterface
{
    /**
     * Return the value of the given key name at the current line
     *
     * @param string $line
     * @param string $keyName
     * @return string
     */
    public function getKey(string $line, string $keyName): string;

    /**
     * Return a md5 hash of the line
     *
     * @param string $line
     * @return string
     */
    public function getHash(string $line): string;
}
