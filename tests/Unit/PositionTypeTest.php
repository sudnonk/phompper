<?php

namespace Tests\Unit;

use App\Domain\ValueObject\Position\PositionType;
use PHPUnit\Framework\TestCase;

class PositionTypeTest extends TestCase
{
    public function testインスタンス化したものの操作()
    {
        $type = PositionType::DENCHU;
        self::assertEquals("電柱", $type->value);
        self::assertEquals("DENCHU", $type->name);
        self::assertTrue($type->equals(PositionType::DENCHU));
        self::assertTrue(PositionType::tryFrom("電柱")->equals(PositionType::DENCHU));
    }

    public function test変な値ではインスタンス化できない()
    {
        $this->expectException(\ValueError::class);
        PositionType::from("hogehoge~~");
    }
}
