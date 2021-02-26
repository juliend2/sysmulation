<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
  public function testMomentEquality(): void
  {
    $moment = Time::fromString('now');
    $event = new Event($moment, 'monthly', -1000, 7);
    $this->assertEquals(
      $moment,
      $event->moment()
    );
  }

  public function testMinValue(): void
  {
    $moment = Time::fromString('2021-01-01 01:01:01');
    $event = new Event($moment, 'monthly', -1000, 7);
    $this->assertEquals(
      '2021-01-01',
      $event->minDate()
    );
  }

  public function testMaxValue(): void
  {
    $moment = Time::fromString('2021-01-01 01:01:01');
    $event = new Event($moment, 'monthly', -1000, 7);
    $this->assertEquals(
      '2021-08-01',
      $event->maxDate()
    );
  }

}