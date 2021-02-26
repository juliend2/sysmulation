<?php declare(strict_types=1);

class Time {

  const DATE_FORMAT = '%Y-%m-%d';

  private $timestamp;

  public function __construct(int $timestamp) {
    $this->timestamp = $timestamp;
  }

  public static function fromString(string $time_expression): self {
    return new self(strtotime($time_expression));
  }

  public function __toString(): string {
    return strftime("%Y-%m-%d %H:%M:%S", $this->timestamp);
  }

  public function daysSince(self $other_time): int {
    return $this->secondsToDays($this->timestamp) - $this->secondsToDays($other_time->timestamp);
  }

  public function timestamp(): int {
    return $this->timestamp;
  }

  public function date(): string {
    return strftime(self::DATE_FORMAT, $this->timestamp());
  }

  public function addToTimestamp(string $interval): int {
    $date = new DateTime(strftime('%Y-%m-%d', $this->timestamp));
    date_add($date, date_interval_create_from_date_string($interval));
    return $date->getTimestamp();
  }

  private function secondsToDays(int $seconds): int {
    return intval(floor($seconds / 60 / 60 / 24));
  }

}
