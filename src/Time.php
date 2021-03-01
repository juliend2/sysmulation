<?php declare(strict_types=1);

class Time {

  const DATE_FORMAT = '%Y-%m-%d';
  const DATETIME_FORMAT = '%Y-%m-%d %H:%M:%S';
  const OLDSCHOOL_DATETIME_FORMAT = 'Y-m-d H:i:s';

  private $timestamp;

  public function __construct(int $timestamp) {
    $this->timestamp = $timestamp;
  }

  public static function fromPast(string $time_expression): self {
    return new self(self::nowDateTime()->sub(date_interval_create_from_date_string($time_expression))->getTimestamp());
  }

  public static function now(): self {
    return new self(
      (self::nowDateTime())->getTimestamp()
    );
  }

  public static function fromString(string $time_expression): self {
    return new self(DateTimeImmutable::createFromFormat(self::OLDSCHOOL_DATETIME_FORMAT, $time_expression, new DateTimeZone( "America/Toronto" ))->getTimestamp());
  }

  private static function nowDateTime(): DateTimeImmutable {
    return new DateTimeImmutable(
        "now", 
        new DateTimeZone( "America/Toronto" )
    );
  }

  public function __toString(): string {
    return strftime(self::DATETIME_FORMAT, $this->timestamp);
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
    $date = new DateTime(strftime(self::DATE_FORMAT, $this->timestamp));
    date_add($date, date_interval_create_from_date_string($interval));
    return $date->getTimestamp();
  }

  private function secondsToDays(int $seconds): int {
    return intval(floor($seconds / 60 / 60 / 24));
  }

}
