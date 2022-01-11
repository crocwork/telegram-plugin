<?php namespace Croqo\Telegram\Classes;

class CommandScope
{
    public function __construct( string $option = 'default' )
    {
        $this->type = $option;
    }
}
