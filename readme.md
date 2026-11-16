Symfony commands as cron jobs
==========================

Using this bundle you can manage cron jobs with yaml configuration of your Symfony application.

It: 
* provides a simple way of running commands according to cron expressions
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
It is recommended to set the timezone (default timezone is UTC and server settings aren't considered).

``` yaml
# config/packages/cron_job_commands.yaml
cron_job_commands:
    timezone: UTC
    schedule:
        command1:
            name: app:command #command name registered in symfony
            enabled: true #default: true
            cron_expression: "* * * * *" #supports also predefined keywords e. g. "@daily", see https://github.com/dragonmantank/cron-expression
            arguments:
                argument1name: value
                argument2name: value
            options:
                option1name: value
                option2name: value
                option3name: # option accepting multiple values (InputOption::VALUE_IS_ARRAY)
                    - value1
                    - value2
                option4name: '%env(json:OPTION4_VALUES)%' # e.g. OPTION4_VALUES='["value1","value2"]'
        command2:
            name: app:another_command
            cron_expression: "@daily"
```

Option values may be scalars or arrays of scalars, given directly or as env placeholders. As env placeholders are
resolved at runtime, the values are validated when the schedule is loaded.

`multivalue_options` (same format as array values in `options`) are deprecated and will be removed in 3.0 - put arrays
of values directly in `options` instead.

Usage
==========================
Run the "trigger-command" once per minute. Consider using quiet mode for less output. 

For cron: add to crontab (`crontab -e`):

```bash
* * * * * /path/to/symfony/bin/console cron_commands:trigger -q
```

In the quiet mode you can still display some messages from your commands setting visibility level as a second parameter of the writeln function (or 3rd parameter of the write function):

``` php
use Symfony\Component\Console\Output\OutputInterface;

/** @var OutputInterface $output */
$output->writeln('<error>Message</error>', OutputInterface::VERBOSITY_QUIET)
```

Requirements
==========================

* PHP 8.0 or above
* Symfony 5.0 or newer

Licence
==========================
_Symfony commands as cron jobs_ is licenced under the MIT Licence.
