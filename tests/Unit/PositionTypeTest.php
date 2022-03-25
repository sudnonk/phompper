<?php

namespace Tests\Unit;

use App\Domain\ValueObject\PositionType;
use App\Exceptions\ValidatorInvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PositionTypeTest extends TestCase
{
    public function testインスタンス化したものの操作()
    {
        $type = new PositionType(PositionType::DENCHU);
        self::assertEquals("電柱", $type->getValue());
        self::assertEquals("DENCHU", $type->getKey());
        self::assertTrue(PositionType::isValidKey('DENCHU'));
        self::assertTrue(PositionType::isValid("電柱"));
        self::assertEquals("DENCHU", PositionType::search("電柱"));
    }

    public function testキーからインスタンス化できる()
    {
        $type = new PositionType(PositionType::DENCHU);
        self::assertEquals("電柱", $type->getValue());
    }

    public function testValueからインスタンス化できる()
    {
        $type = new PositionType("電柱");
        self::assertEquals("電柱", $type->getValue());
    }

    public function test変な値ではインスタンス化できない()
    {
        $this->expectException(ValidatorInvalidArgumentException::class);
        new PositionType("poe~~~");
    }
}
