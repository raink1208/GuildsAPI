<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Label;
use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;
use rain1208\guildsAPI\guilds\Guild;

class GuildInfoForm extends AbstractCustomForm
{
    private Form $back;

    public function __construct(Guild $guild, Form $back)
    {
        $this->back = $back;

        $title = $guild->getName()."の情報";
        $elements = [
            new Label("guildInfo", $guild->getGuildInfoString())
        ];
        parent::__construct($title, $elements);
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {
        $player->sendForm($this->back);
    }
}