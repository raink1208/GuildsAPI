<?php


namespace rain1208\guildsAPI\forms\addons;


use Closure;
use dktapps\pmforms\ModalForm;
use pocketmine\Player;

abstract class AbstractModalForm extends ModalForm
{
    public function __construct(string $title, string $text, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no")
    {
        parent::__construct(
            $title,
            $text,
            Closure::fromCallable([$this, "submit"]),
            $yesButtonText,
            $noButtonText
        );
    }

    abstract public function submit(Player $player, bool $bool): void;
}