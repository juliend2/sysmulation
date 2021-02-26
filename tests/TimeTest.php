<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TimeTest extends TestCase
{
  public function testTimeIsTwoDaysEarlier(): void
  {
    $t1 = Time::fromString('2 days ago');
    $t2 = Time::fromString('now');
    $this->assertEquals(
      2,
      $t2->daysSince($t1)
    );
  }

  public function testStringFormatting(): void
  {
    $time_string = '2020-01-02 03:52:36';
    $t = Time::fromString($time_string);
    $this->assertEquals(
      $time_string,
      ''.$t.'' // string context
    );
  }

}
