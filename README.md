# document-hash-indexer

This library allows you to generate an index of hashed values from a csv file or a list of json strings.

The result is a yaml file that can be easily used for quick diffs in order to spot changes, additions and deletions.

### Pre-requisite
* php 7.1
* ext-json

### Installation
Run `composer require skyree/document-hash-indexer` from your project.

### Getting started
All it takes is to instantiate a `HashIndexer` and run the `hash` method with the desired key.
#### Example
```php
$hashIndexer = new HashIndexer(new JsonParser(), new LazyErrorHandler());
$outputFile = $hashIndexer->hash('file.json', 'foo');
echo file_get_contents($outputFile);
```
with `file.json` containing the following
```
{"foo": "aaa", "bar": [{"a":  1, "b": 2}]}
{"foo": "bbb", "bar": [{"y":  4, "z": 5}]}
```
will result in
```yaml
aaa: 7dc9c52dbf3e5a436ac2a40affed4d16
bbb: 288a8a3a1c2a8a370ea20b88b9b0f426
```

**Important note**: As mentioned in the description, for json documents, the expected input is list of json strings, not a properly formatted json object.

**Important note 2**: Make sure your input document has a unique key (a csv column whose values are all different or a json node whose value differs for each line), otherwise the generated yaml will be invalid as duplicates are forbidden.
#### Parsers
This library comes with 2 parsers:
* `ParserJson`
* `ParserCsv`

You can create and use your own parsers by implementing `ParserInterface`

#### ErrorHandlers
These handlers are used to provide a behavior when a key is not found on a line;
This library comes with 2 handlers:
* `LazyErrorHandler` which basically ignores the error
* `ThresholdErrorHandler` which allows up to n errors before interrupting

You can create and use your own handlers by implementing `ErrorHandlerInterface`

### Integration with Symfony
This library can easily be defined as one or multiple services
```yaml
Skyree\DocumentHashIndexer\Parser\JsonParser: ~

Skyree\DocumentHashIndexer\Parser\CsvParser: ~

Skyree\DocumentHashIndexer\ErrorHandler\LazyErrorHandler: ~

my_project.json_hash_indexer:
    class: Skyree\DocumentHashIndexer\HashIndexer
    arguments:
        $parser: '@Skyree\DocumentHashIndexer\Parser\JsonParser'
        $errorHandler: '@Skyree\DocumentHashIndexer\ErrorHandler\LazyErrorHandler'

my_project.csv_hash_indexer:
    class: Skyree\DocumentHashIndexer\HashIndexer
    arguments:
        $parser: '@Skyree\DocumentHashIndexer\Parser\CsvParser'
        $errorHandler: '@Skyree\DocumentHashIndexer\ErrorHandler\LazyErrorHandler'
```
