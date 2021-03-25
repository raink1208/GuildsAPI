<?php


namespace rain1208\guildsAPI\forms\addons;


use Closure;
use dktapps\pmforms\MenuForm;
use pocketmine\Player;

abstract class AbstractMenuForm extends MenuForm
{
    public function __construct(string $title, string $text, array $options)
    {
        parent::__construct(
            $title,
            $text,
            $options,
            Closure::fromCallable([$this, "submit"]),
            Closure::fromCallable([$this, "close"])
        );
    }

    abstract public function submit(Player $player, int $select): void;

    public function close(Player $player): void
    {
    }
}