<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\ErrorHandler;

use Skyree\DocumentHashIndexer\Parser\KeyNotFoundException;

/**
 * Class LazyErrorHandler
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
class LazyErrorHandler implements ErrorHandlerInterface
{
    /**
     * Allow all errors
     *
     * @param int $errorCount
     * @param KeyNotFoundException $exception
     */
    public function handle(int $errorCount, KeyNotFoundException $exception): void
    {
        return;
    }
}
