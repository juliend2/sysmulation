<?php declare(strict_types=1);

class Time {

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

  private function secondsToDays(int $seconds): int {
    return intval(floor($seconds / 60 / 60 / 24));
  }

}
