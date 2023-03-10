# tootbot-template

## getting started

### first things first
- clone this repository
- create a mastodon account for your bot, e.g. on https://botsin.space/
- when your account is approved, go to the [development settings](https://botsin.space/settings/applications) and create a new application
- copy [`/config/.env_example`](./config/.env_example) to `/config/.env`
  - copy the access token from the mastodon application and save it in the `.env` as `MASTODON_TOKEN`, go to the repository settings on GitHub under `{repo_url}/settings/secrets/actions` and save it there too (not necessary if you plan to run the bot on your own webserver)
  - save the mastodon instance URL in the `.env` as `MASTODON_INSTANCE`, save it as GitHub repository secret as well
  - if you plan to use remote authentication with the mastodon app, you will need to do the same for the client key, secret and callback-URL
- fetch a fresh `cacert.pem` from https://curl.se/ca/cacert.pem and save it under `/config`

### next up: code
- update the [`LICENSE`](./LICENSE)
- change the example namespaces in [`composer.json`](./composer.json), add any libraries you need, add yourself as author
  - commit the `composer.lock` after updating
- change/replace the [`MyTootBot`](./src/MyTootBot.php) and [`MyTootBotTest`](./tests/MyTootBotTest.php) examples
  - `MyTootBot` needs to extend the abstract `TootBot` class
- update the CLI runner [`run.php`](./cli/run.php) as necessary

### finally: run
- test locally: `php ./cli/run.php`
- create a `run.yml` in [`/.github/workflows`](./.github/workflows) which enables a scheduled GitHub action (see below)
- profit!

```yml
# https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions

on:
  schedule:
    # run the script every 12th hour
    # https://pubs.opengroup.org/onlinepubs/9699919799/utilities/crontab.html
    - cron: "0 12 * * *"

name: "Run"

jobs:

  run-bot:
    name: "Run the bot and post to Mastodon"

    runs-on: ubuntu-latest

    env:
      MASTODON_TOKEN: ${{ secrets.MASTODON_TOKEN }}
      MASTODON_INSTANCE: ${{ secrets.MASTODON_INSTANCE }}

    steps:
      - name: "Checkout sources"
        uses: actions/checkout@v3

      - name: "Install PHP"
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.2"
          coverage: none
          extensions: curl, json, mbstring, openssl, sodium

      - name: "Install dependencies with composer"
        uses: ramsey/composer-install@v2

      - name: "Fetch cacert.pem from curl.haxx.se"
        run: wget -O config/cacert.pem https://curl.se/ca/cacert.pem

      - name: "Run bot"
        run: php ./cli/run.php

      # please note that this requires read/write permissions for the actions runner!
      - name: "Commit log"
        run: |
          git config user.name github-actions
          git config user.email github-actions@github.com
          git add data/posted.json
          git commit -m "posted toot"
          git push
```

## disclaimer

WE'RE TOTALLY NOT RUNNING A PRODUCTION-LIKE ENVIRONMENT ON GITHUB.<br>
WE'RE RUNNING A TEST AND POST THE RESULT TO AN EXTERNAL WEBSITE.<br>
WE'RE JUST LOOKING IF THE SCRIPT STILL WORKS ON A SCHEDULE N TIMES A DAY.
