<?php declare(strict_types=1);

class Event {

  const DATE_FORMAT = '%Y-%m-%d';

  private $moment;
  private $interval;
  private $repetitions;
  private $stock_change;

  public function __construct(Time $moment, string $interval, int $stock_change, int $reps = 0) {
    $this->moment = $moment;
    $this->interval = $interval;
    $this->stock_change = $stock_change;
		$this->repetitions = $reps;
  }

  public function stockChange(): int {
    return $this->stock_change;
  }

  public function moment(): Time {
    return $this->moment;
  }

  public function minDate(): string {
    return $this->moment->date();
  }

  public function repetitions(): int {
    return $this->repetitions;
  }

  public function minTimestamp(): int {
    return strtotime($this->minDate());
  }

  public function maxTimestamp(): int {
    return strtotime($this->maxDate());
  }

  public function maxDate(): string {
    return strftime(self::DATE_FORMAT, $this->moment->addToTimestamp($this->whatToAdd()));
  }

  // Get the repetition's timestamp
  public function timestampForRepetition($n): int {
    return $this->moment->addToTimestamp($this->whatToAdd($n));
  }

  public function daysSince(self $other_event): int {
    return intval((strtotime($this->maxDate()) - $other_event->minTimestamp()) / 60 / 60 / 24);
  }

  private function whatToAdd(int $n = null): string {
    if ($n === null) {
      $n = $this->repetitions;
    }
    return $n." ".$this->interval();
  }

  private function interval(): string {
    return [
      'yearly' => 'years',
      'monthly' => 'months',
      'weekly' => 'weeks',
      'daily' => 'days',
      'hourly' => 'hours',
      'every minute' => 'minutes',
      'every second' => 'seconds',
    ][$this->interval];
  }

}