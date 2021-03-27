<?php


namespace rain1208\guildsAPI\forms;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\create\GuildCreateForm;
use rain1208\guildsAPI\forms\guilds\GuildMenuForm;
use rain1208\guildsAPI\forms\guilds\join\JoinMenuForm;
use rain1208\guildsAPI\forms\lists\MoneySortedList;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;

class MainForm extends AbstractMenuForm
{
    public function __construct()
    {
        $title = "Guild Main Form";
        $text = "";
        $options = [
            new MenuOption("参加しているギルドの確認"),
            new MenuOption("ギルドに参加"),
            new MenuOption("所持金ランキング"),
            new MenuOption("ギルドの作成")
        ];
        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());
        switch ($this->getOption($select)->getText()) {
            case "参加しているギルドの確認":
                if ($guildPlayer->getGuildId() === GuildId::NO_GUILD) {
                    $player->sendForm(new ErrorForm("ギルドに参加していません", $this));
                    return;
                }
                $player->sendForm(new GuildMenuForm($guildPlayer));
                break;
            case "ギルドに参加":
                $player->sendForm(new JoinMenuForm()); //ギルドへの参加
                break;
            case "所持金ランキング":
                $player->sendForm(new MoneySortedList());
                break;
            case "ギルドの作成":
                if ($guildPlayer->getGuildId() !== GuildId::NO_GUILD) {
                    $player->sendForm(new ErrorForm("既にギルドに参加しています\n新しく作るには今いるギルドを退出してください", $this));
                    return;
                }
                $player->sendForm(new GuildCreateForm());//ギルドの作成
                break;
        }
    }
}