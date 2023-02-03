<?php
/**
 * Class MyTootBotTest
 *
 * @created      03.02.2023
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2023 smiley
 * @license      MIT
 */

namespace PHPTootBot\MyTootBotTest;

use PHPTootBot\MyTootBot\MyTootBot;
use PHPTootBot\PHPTootBot\TootBotInterface;
use PHPTootBot\PHPTootBot\TootBotOptions;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class MyTootBotTest extends TestCase{

	public function testInstance():void{
		$this::assertInstanceOf(TootBotInterface::class, new MyTootBot(new TootBotOptions));
	}

}
