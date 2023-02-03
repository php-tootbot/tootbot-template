<?php
/**
 * common.php
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

require_once __DIR__.'/../vendor/autoload.php';

// if we're running on gh-actions, we're going to fetch the variables from gh.secrets,
if(isset($_SERVER['GITHUB_ACTIONS'])){
	$instance = getenv('MASTODON_INSTANCE');
	$apiToken = getenv('MASTODON_TOKEN');
}
// otherwise we're loading them from the local .env file
else{
	$env = (new DotEnv(__DIR__.'/../config', '.env', false))->load();

	$instance = $env->get('MASTODON_INSTANCE');
	$apiToken = $env->get('MASTODON_TOKEN');
}


// invoke the options instance
$options = new TootBotOptions;

// HTTPOptions
$options->ca_info      = realpath(__DIR__.'/../config/cacert.pem'); // https://curl.haxx.se/ca/cacert.pem
$options->user_agent   = 'phpTootBot/1.0 +https://github.com/php-tootbot/php-tootbot';

// OAuthOptionsTrait
// these settings are only required for authentication/remote token acquisition
#$options->key          = $env->get('MASTODON_KEY') ?? '';
#$options->secret       = $env->get('MASTODON_SECRET') ?? '';
#$options->callbackURL  = $env->get('MASTODON_CALLBACK_URL') ?? '';
#$options->sessionStart = true;

// TootBotOptions
$options->instance     = $instance;
$options->apiToken     = $apiToken;
$options->loglevel     = LogLevel::INFO;
#$options->buildDir     = __DIR__.'/../.build';
$options->dataDir      = __DIR__.'/../data';

// invoke the bot instance and post
(new MyTootBot($options))->post();

exit;
