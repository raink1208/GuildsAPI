<?php


namespace rain1208\guildsAPI\forms\addons;


use Closure;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\ServerSettingsForm;
use pocketmine\Player;

abstract class AbstractServerSettingsForm extends ServerSettingsForm
{
    public function __construct(string $title, array $elements, ?FormIcon $icon, Closure $onSubmit, ?Closure $onClose = null)
    {
        parent::__construct(
            $title,
            $elements,
            $icon,
            Closure::fromCallable([$this, "submit"]),
            Closure::fromCallable([$this, "close"])
        );
    }

    abstract public function submit(Player $player, CustomFormResponse $response): void;

    public function close(Player $player): void
    {

    }
}