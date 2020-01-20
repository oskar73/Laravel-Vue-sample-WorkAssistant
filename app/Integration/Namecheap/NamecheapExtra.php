<?php

namespace App\Integration\Namecheap;

use Namecheap\Api;

class NamecheapExtra extends Api
{
    /**
             * Array of possible commands and associated class names
             *
             * @var array
             */
    protected static $_commands = [
            'domains.setLock' => \App\Integration\Namecheap\DomainSetLock::class,
            'domains.getLock' => \App\Integration\Namecheap\DomainGetLock::class,
            'domains.setContacts' => \App\Integration\Namecheap\DomainSetContacts::class,
        ];

    /**
     * @param $config
     * @param $command
     *
     * @return \Namecheap\Command\ACommand
     * @throws Api\Exception
     */
    public static function factory($config, $command)
    {
        if (! array_key_exists($command, static::$_commands)) {
            throw new Api\Exception($command . ' is not a valid API');
        }

        $instance = new static::$_commands[$command]();

        return $instance->config($config);
    }
}
