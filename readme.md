Symfony commands as cron jobs
==========================

Using this bundle you can manage cron jobs using yaml configuration of your Symfony application.

It: 
* provides simple way of running commands according to cron expressions
* supports command arguments, options and verbosity settings
* prints full output of running commands
* uses https://github.com/dragonmantank/cron-expression to resolve cron expressions

Installing
==========================
Update composer.json:
```bash
composer require mkijak/cronjob-commands-bundle
```
Enable the bundle in Symfony application:
```php
# config/bundles.php 

Mkijak\CronJobCommandsBundle\CronJobCommandsBundle::class => ['all' => true],
```

Configuration
==========================
It is recommended to set timezone (default is UTC and server settings aren't considered).

``` yaml
# config/packages/cron_job_commands.yaml
cron_job_commands:
    timezone: UTC
    schedule:
        command1:
            name: app:command
            cron_expression: "* * * * *" #supports also predefined keywords e. g. "@daily", see https://github.com/dragonmantank/cron-expression
            arguments:
                argument1name: value
                argument2name: value
            options:
                option1name: value
                option2name: value
        command2:
            name: app:another_command
            cron_expression: "@daily"
```

Usage
==========================
Run trigger-command once per minute. Consider using quiet mode for less output. Add to crontab (`crontab -e`):

```bash
* * * * * /path/to/symfony/bin/console cron_commands:trigger -q
```

In quiet mode you can still display some messages from your commands setting visibility level in second parameter of writeln function (or 3rd parameter of write function):

``` php
use Symfony\Component\Console\Output\OutputInterface;

/** @var OutputInterface $output */
$output->writeln('<error>Message</error>', OutputInterface::VERBOSITY_QUIET)
```

Requirements
==========================

* PHP 7.1 or above
* Symfony 4.0 or newer

Licence
==========================
Symfony commands as cron jobs is licenced under the MIT Licence.
