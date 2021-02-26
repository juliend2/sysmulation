<?php declare(strict_types=1);

class Event {

  const DATE_FORMAT = '%Y-%m-%d';

  private $moment;
  private $interval;
  private $repetitions;

  public function __construct(Time $moment, string $interval, int $reps) {
    $this->moment = $moment;
    $this->interval = $interval;
		$this->repetitions = $reps;
  }

  public function moment(): Time {
    return $this->moment;
  }

  public function minDate(): string {
    return strftime(self::DATE_FORMAT, $this->moment->timestamp());
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

  private function whatToAdd(): string {
    return $this->repetitions." ".$this->interval();
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
