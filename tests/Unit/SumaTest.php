<?php

use PHPUnit\Framework\TestCase;

class SumaTest extends TestCase {

    public function test_sum(): void {
        /**
         * @var int
         */
        $sum = 2 + 2;

        $this->assertEquals(4, $sum);
    }
}