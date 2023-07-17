<?php
/**
 * Class MyTootBotTest
 *
 * @todo: update/change docblock
 *
 * @created      03.02.2023
 * @author       you
 * @copyright    2023 you
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
