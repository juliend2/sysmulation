<?php declare(strict_types=1);


use PHPUnit\Framework\TestCase;

final class StockTest extends TestCase
{

  public function testMaxValue(): void
  {
    $stock = new Stock(8000, [
        new Event(Time::fromString('2021-01-04 01:01:01'), 'monthly', -1000),
        new Event(Time::fromString('2021-01-04 01:01:01'), 'yearly', -1000),
        new Event(Time::fromString('2021-01-01 01:01:01'), 'monthly', -1000),
        new Event(Time::fromString('2021-01-07 01:01:01'), 'monthly', -1000),
    ]);
    $this->assertEquals(
      7000,
      $stock->getStockValueAt(Time::fromString('2021-01-02 01:01:01'))
    );
  }

}