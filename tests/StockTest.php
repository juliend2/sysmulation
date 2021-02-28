<?php declare(strict_types=1);


use PHPUnit\Framework\TestCase;

final class StockTest extends TestCase
{

  public function testStockAfterManyEvents(): void
  {
    $stock = new Stock(8000, [
        new Event(Time::fromString('2021-01-04 01:01:01'), 'monthly', -1000),
        new Event(Time::fromString('2021-01-04 01:01:01'), 'yearly', -1000),
        new Event(Time::fromString('2021-01-01 01:01:01'), 'monthly', -1000),
        new Event(Time::fromString('2021-01-07 01:01:01'), 'monthly', -1000),
    ]);
    //var_dump($stock->timestampsWithStocks());
    $this->assertEquals(
      7000,
      $stock->getStockValueAt(Time::fromString('2021-01-02 01:01:01'))
    );
  }

  public function testStockAfterTwoRepetitionOfSameEvent(): void {
      $stock = new Stock(1000, [
        new Event(Time::fromString('2021-01-01 00:00:00'), 'monthly', -100, 2)
      ]);
      $this->assertEquals(
        900,
        $stock->timestampsWithStocks()[1609477200] // 1 jan 00:00
      );
      $this->assertEquals(
        800,
        $stock->timestampsWithStocks()[1612155600] // 1 fev 00:00
      );
      $this->assertEquals(
        700,
        $stock->timestampsWithStocks()[1614574800] // 1 mars 00:00
      );
  }

  public function testFirstDateInTimeRange(): void {
    $stock = new Stock(1000, [
        new Event(Time::fromString('2021-01-01 00:00:00'), 'monthly', -100, 7)
    ]);
    $this->assertEquals(
        1609477200,
        array_key_first( $stock->timestampsWithStocks() )
    );
  }

}