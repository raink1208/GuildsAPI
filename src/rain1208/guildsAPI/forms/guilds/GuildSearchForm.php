<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\CustomFormResponse;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;

class GuildSearchForm extends AbstractCustomForm
{
    public function __construct()
    {
        $title = "";
        $elements = [

        ];
        parent::__construct($title, $elements);
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {
        // TODO: Implement submit() method.
    }
}