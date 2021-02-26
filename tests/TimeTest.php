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

}
