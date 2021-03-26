<?php


namespace rain1208\guildsAPI\forms;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\GuildMenuForm;
use rain1208\guildsAPI\Main;

class MainForm extends AbstractMenuForm
{
    public function __construct()
    {
        $title = "Guild Main Form";
        $text = "";
        $options = [
            new MenuOption("参加しているギルドの確認"),
            new MenuOption("ギルドに参加"),
            new MenuOption("ギルドの一覧"),
            new MenuOption("所持金ランキング"),
            new MenuOption("ギルドの作成")
        ];
        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());
        switch ($select) {
            case 0:
                $player->sendForm(new GuildMenuForm($guildPlayer));
        }
    }
}