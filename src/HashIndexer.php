<?php declare(strict_types=1);

namespace Skyree\DocumentHashIndexer;

use Skyree\DocumentHashIndexer\ErrorHandler\ErrorHandlerInterface;
use Skyree\DocumentHashIndexer\Parser\KeyNotFoundException;
use Skyree\DocumentHashIndexer\Parser\ParserInterface;

/**
 * Class HashIndexer
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
class HashIndexer
{
    /** @var ParserInterface */
    private $parser;

    /** @var ErrorHandlerInterface */
    private $errorHandler;

    /**
     * HashIndexer constructor.
     *
     * @param ParserInterface $parser
     * @param ErrorHandlerInterface $errorHandler
     */
    public function __construct(ParserInterface $parser, ErrorHandlerInterface $errorHandler)
    {
        $this->parser = $parser;
        $this->errorHandler = $errorHandler;
    }

    /**
     * Read and index an input file into a yaml file then output its path
     *
     * @param string $fileName
     * @param string $keyName
     * @param string $outputPath
     * @return string
     * @throws
     */
    public function hash(string $fileName, string $keyName, string $outputPath = ''): string
    {
        $file = $this->getFile($fileName);
        $outputFile = $this->getOutputFile($fileName, $outputPath);

        $errors = 0;
        try {
            foreach ($file as $line) {
                if (empty($line)) {
                    continue;
                }

                try {
                    $key = $this->parser->getKey($line, $keyName);
                    $outputFile->fwrite($key . ': ' . $this->parser->getHash($line) . PHP_EOL);
                } catch (KeyNotFoundException $e) {
                    $this->errorHandler->handle(++$errors, $e);
                }
            }
        } catch (\Throwable $exception) {
            $outputFilePath = $outputFile->getFileInfo()->getPathname();
            $outputFile = null;
            unlink($outputFilePath);

            throw $exception;
        }

        return $outputFile->getFileInfo()->getPathname();
    }

    /**
     * @param string $fileName
     * @return \SplFileObject
     * @throws
     */
    private function getFile(string $fileName): \SplFileObject
    {
        $info = pathinfo($fileName);
        if ($info['extension'] === 'gz') {
            $fileName = 'compress.zlib://' . $fileName;
        }

        return new \SplFileObject($fileName);
    }

    /**
     * @param string $fileName
     * @param string $outputPath
     * @return \SplFileObject
     */
    private function getOutputFile(string $fileName, string $outputPath): \SplFileObject
    {
        $info = pathinfo($fileName);
        if (empty($outputPath)) {
            $outputPath = $info['dirname'];
        }

        if (!is_dir($outputPath) && !mkdir($outputPath)) {
            throw new \RuntimeException('The specified output path does not exist and cannot be created');
        }

        $fileName = $outputPath . DIRECTORY_SEPARATOR . $info['filename'] . '-' . date('YmdHis') . '.yml';
        return new \SplFileObject($fileName, 'w+');
    }
}
