<?php declare(strict_types=1);

use Skyree\DocumentHashIndexer\ErrorHandler\LazyErrorHandler;
use Skyree\DocumentHashIndexer\ErrorHandler\ThresholdErrorException;
use Skyree\DocumentHashIndexer\ErrorHandler\ThresholdErrorHandler;
use Skyree\DocumentHashIndexer\HashIndexer;
use Skyree\DocumentHashIndexer\Parser\CsvParser;
use Skyree\DocumentHashIndexer\Parser\JsonParser;

/**
 * Class HashIndexerTest
 *
 * @author Skyree <boulakras.loic@gmail.com>
 */
class HashIndexerTest extends PHPUnit\Framework\TestCase
{
    public function testItIndexJson()
    {
        $hashIndexer = new HashIndexer(new JsonParser(), new LazyErrorHandler());
        $outputPath = $hashIndexer->hash(__DIR__ . '/samples/test.json', 'foo');

        $expected = "aaa: 7dc9c52dbf3e5a436ac2a40affed4d16\nbbb: 288a8a3a1c2a8a370ea20b88b9b0f426\n";
        $this->assertEquals($expected, file_get_contents($outputPath));
        unlink($outputPath);
    }

    public function testItIndexCompressedJson()
    {
        $hashIndexer = new HashIndexer(new JsonParser(), new LazyErrorHandler());
        $outputPath = $hashIndexer->hash(__DIR__ . '/samples/test.json.gz', 'foo');

        $expected = "aaa: 7dc9c52dbf3e5a436ac2a40affed4d16\nbbb: 288a8a3a1c2a8a370ea20b88b9b0f426\n";
        $this->assertEquals($expected, file_get_contents($outputPath));
        unlink($outputPath);
    }

    public function testItIndexCsv()
    {
        $hashIndexer = new HashIndexer(new CsvParser(), new LazyErrorHandler(1));
        $outputPath = $hashIndexer->hash(__DIR__ . '/samples/test.csv', '0');

        $expected = "aaa: 7a9a3118777f5cfe34ac23a9fa8666dd\nbbb: e57895e34fa6f5520282bb7ec237e506\n";
        $this->assertEquals($expected, file_get_contents($outputPath));
        unlink($outputPath);
    }

    public function testItDoesNotRaiseExceptionWithLazyHandler()
    {
        $hashIndexer = new HashIndexer(new JsonParser(), new LazyErrorHandler());
        $outputPath = $hashIndexer->hash(__DIR__ . '/samples/test.json', 'qux');

        $this->assertEquals('', file_get_contents($outputPath));
        unlink($outputPath);
    }

    public function testItDoesNotRaiseExceptionWithHighThreshold()
    {
        $hashIndexer = new HashIndexer(new JsonParser(), new ThresholdErrorHandler(5));
        $outputPath = $hashIndexer->hash(__DIR__ . '/samples/test.json', 'qux');

        $this->assertEquals('', file_get_contents($outputPath));
        unlink($outputPath);
    }

    public function testItRaiseThresholdExceptionWithJsonFile()
    {
        $hashIndexer = new HashIndexer(new JsonParser(), new ThresholdErrorHandler(1));
        $this->expectException(ThresholdErrorException::class);
        $hashIndexer->hash(__DIR__ . '/samples/test.json', 'qux');
    }

    public function testItRaiseThresholdExceptionWithCsvFile()
    {
        $hashIndexer = new HashIndexer(new CsvParser(), new ThresholdErrorHandler(1));
        $this->expectException(ThresholdErrorException::class);
        $hashIndexer->hash(__DIR__ . '/samples/test.csv', 'qux');
    }

    public function testItRaiseJsonException()
    {
        $hashIndexer = new HashIndexer(new JsonParser(), new ThresholdErrorHandler(1));
        $this->expectException(\Safe\Exceptions\JsonException::class);
        $hashIndexer->hash(__DIR__ . '/samples/test.csv', 'qux');
    }
}
