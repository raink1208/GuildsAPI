<?php


namespace rain1208\guildsAPI\forms\addons;


use Closure;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use pocketmine\Player;

abstract class AbstractCustomForm extends CustomForm
{
    public function __construct(string $title, array $elements)
    {
        parent::__construct(
            $title,
            $elements,
            Closure::fromCallable([$this, "submit"]),
            Closure::fromCallable([$this, "close"])
        );
    }

    abstract public function submit(Player $player, CustomFormResponse $response): void;

    public function close(Player $player): void
    {
    }
}