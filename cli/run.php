<?php
/**
 * TootBot CLI runner
 *
 * @created      03.12.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

use chillerlan\DotEnv\DotEnv;
use PHPTootBot\MyTootBot\MyTootBot;
use PHPTootBot\PHPTootBot\TootBotOptions;
use Psr\Log\LogLevel;

ini_set('date.timezone', 'UTC');
#mb_internal_encoding('UTF-8');

require_once __DIR__.'/../vendor/autoload.php';

// if we're running on gh-actions, we're going to fetch the variables from gh.secrets,
// otherwise we'll load them from the local .env file into the global environment
if(!isset($_SERVER['GITHUB_ACTIONS'])){
	(new DotEnv(__DIR__.'/../config', '.env', true))->load();
}

// invoke the options instance
$options = new TootBotOptions;

// HTTPOptions
$options->ca_info        = realpath(__DIR__.'/../config/cacert.pem'); // https://curl.haxx.se/ca/cacert.pem
$options->user_agent     = 'phpTootBot/1.0 +https://github.com/php-tootbot/php-tootbot';
$options->retries        = 3;

// OAuthOptionsTrait
// these settings are only required for authentication/remote token acquisition
#$options->key            = getenv('MASTODON_KEY');
#$options->secret         = getenv('MASTODON_SECRET');
#$options->callbackURL    = getenv('MASTODON_CALLBACK_URL');
#$options->sessionStart   = true;

// TootBotOptions
$options->instance       = getenv('MASTODON_INSTANCE');
$options->apiToken       = getenv('MASTODON_TOKEN');
$options->loglevel       = LogLevel::INFO;
#$options->buildDir       = __DIR__.'/../.build';
$options->dataDir        = __DIR__.'/../data';
$options->tootVisibility = 'public';

// invoke the bot instance and post
(new MyTootBot($options))->post();

exit;
