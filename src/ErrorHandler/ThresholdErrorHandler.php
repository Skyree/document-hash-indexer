<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer\ErrorHandler;

use Skyree\DocumentHashIndexer\Parser\KeyNotFoundException;

/**
 * Class ThresholdErrorHandler
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
class ThresholdErrorHandler implements ErrorHandlerInterface
{
    /** @var int */
    private $threshold;

    /**
     * ThresholdErrorHandler constructor.
     *
     * @param int $threshold
     */
    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * Allow $threshold errors
     *
     * @param int $errorCount
     * @param KeyNotFoundException $exception
     */
    public function handle(int $errorCount, KeyNotFoundException $exception): void
    {
        if ($errorCount >= $this->threshold) {
            throw new ThresholdErrorException(sprintf('Missing key over %d times', $this->threshold), 0, $exception);
        }
    }
}
