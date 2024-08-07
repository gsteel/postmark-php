<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Postmark\Models\Header;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class HeaderTest extends TestCase
{
    public function testThatHeadersHaveTheExpectedValues(): void
    {
        $header = new Header('Foo', 'Bar');
        self::assertEquals([
            'Name' => 'Foo',
            'Value' => 'Bar',
        ], $header->jsonSerialize());
    }

    public function testThatAnArrayCanBeConvertedIntoAList(): void
    {
        $input = [
            'some' => 'thing',
            'other' => 'thing',
        ];

        $headers = Header::listFromArray($input);
        self::assertNotNull($headers);
        self::assertCount(2, $headers);
        self::assertContainsOnlyInstancesOf(Header::class, $headers);

        $expect = json_encode([
            ['Name' => 'some', 'Value' => 'thing'],
            ['Name' => 'other', 'Value' => 'thing'],
        ], JSON_THROW_ON_ERROR);

        self::assertJsonStringEqualsJsonString($expect, json_encode($headers, JSON_THROW_ON_ERROR));
    }

    public function testThatListReturnIsNullForAnEmptyInputArray(): void
    {
        self::assertNull(Header::listFromArray([]));
    }

    public function testThatHeaderInstancesCanBeUsed(): void
    {
        $input = [
            'whatever' => new Header('X-Some-Header', 'Foo'),
            'X-Other-Header' => 'Bar',
        ];

        $expect = json_encode([
            ['Name' => 'X-Some-Header', 'Value' => 'Foo'],
            ['Name' => 'X-Other-Header', 'Value' => 'Bar'],
        ], JSON_THROW_ON_ERROR);

        $headers = Header::listFromArray($input);

        self::assertJsonStringEqualsJsonString($expect, json_encode($headers, JSON_THROW_ON_ERROR));
    }

    public function testThatEmptyKeysAreIgnored(): void
    {
        $input = [
            0 => 'Some Value',
            1 => 'Another Value',
            '' => 'Not There',
            'foo' => 'bar',
        ];

        $expect = json_encode([
            ['Name' => '0', 'Value' => 'Some Value'],
            ['Name' => '1', 'Value' => 'Another Value'],
            ['Name' => 'foo', 'Value' => 'bar'],
        ], JSON_THROW_ON_ERROR);

        $headers = Header::listFromArray($input);

        self::assertJsonStringEqualsJsonString($expect, json_encode($headers, JSON_THROW_ON_ERROR));
    }
}
