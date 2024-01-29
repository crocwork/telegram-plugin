<?php namespace Crocwork\Telegram\Classes;

class CommandScope
{
    public function __construct( string $option = 'default' )
    {
        $this->type = $option;
    }
}
