<?php declare(strict_types=1);
/*
 * Copyright (c) Cristiano Cinotti 2024.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *  http://www.apache.org/licenses/LICENSE-2.0
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

use org\bovigo\vfs\vfsStream;
use Susina\XmlToArray\Exception\ConverterException;
use Susina\XmlToArray\Converter;

beforeEach(function () {
    $this->converter = new Converter();
});

it('converts xml to array', function (string $xml, array $expected) {
    $actual = $this->converter->convert($xml);
    expect($actual)->toBe($expected);
})->with('Xml');

it('converts xml with inclusion', function (string $xmlLoad, string $xmlInclude, array $expected) {
    vfsStream::newFile('testconvert_include.xml')->at($this->getRoot())->setContent($xmlInclude);
    $actual = $this->converter->convert($xmlLoad);
    expect($actual)->toBe($expected);
})->with('Inclusion');

it('converts an invalid xml', function () {
    $invalidXml = <<< INVALID_XML
No xml
only plain text
---------
INVALID_XML;
    $this->converter->convert($invalidXml);
})->throws(ConverterException::class, "An error occurred while parsing XML string:
 - Fatal 4: Start tag expected, '<' not found
");

it('finds error in xml content', function () {
    $xmlWithError = <<< XML
<?xml version='1.0' standalone='yes'?>
<movies>
 <movie>
  <titles>Star Wars</title>
 </movie>
 <movie>
  <title>The Lord Of The Rings</title>
 </movie>
</movies>
XML;
    $this->converter->convert($xmlWithError);
})->throws(ConverterException::class, "An error occurred while parsing XML string:
 - Fatal 76: Opening and ending tag mismatch: titles line 4 and title
");

it('finds multiple errors in xml', function () {
    $xmlWithErrors = <<< XML
<?xml version='1.0' standalone='yes'?>
<movies>
 <movie>
  <titles>Star Wars</title>
 </movie>
 <movie>
  <title>The Lord Of The Rings</title>
 </movie>
</moviess>
XML;
    $this->converter->convert($xmlWithErrors);
})->throws(ConverterException::class, "Some errors occurred while parsing XML string:
 - Fatal 76: Opening and ending tag mismatch: titles line 4 and title
 - Fatal 76: Opening and ending tag mismatch: movies line 2 and moviess
");

it('converts Id attribute into an associative array key', function (string $xml, array $expected) {
    $actual = Converter::create()->convert($xml);
    expect($actual)->toBe($expected);
})->with('TestId')->toDo('Implement this feature');
