<?php


namespace rain1208\guildsAPI\forms\guilds\create;


use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Label;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;
use rain1208\guildsAPI\guilds\Guild;

class GuildCreateCompleteForm extends AbstractCustomForm
{
    public function __construct(Guild $guild)
    {
        $title = "ギルド作成完了";
        $elements = [
            new Label("label", "ギルドを作成しました"),
            new Label("label1", $guild->getGuildInfoString())
        ];
        parent::__construct($title, $elements);
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {

    }
}