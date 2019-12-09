<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\ErrorHandler;

use Skyree\DocumentHashIndexer\Parser\KeyNotFoundException;

/**
 * Interface ErrorHandlerInterface
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
interface ErrorHandlerInterface
{
    /**
     * Error handling behavior
     *
     * @param int $errorCount
     * @param KeyNotFoundException $exception
     */
    public function handle(int $errorCount, KeyNotFoundException $exception): void;
}
