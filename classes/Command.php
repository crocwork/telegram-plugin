<?php namespace Croqo\Telegram\Classes;

class Command
{
    public string $command;
    public string $description;
    private CommandScope $scope;
}
