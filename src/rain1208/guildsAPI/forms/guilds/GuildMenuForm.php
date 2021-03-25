<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\guilds\GuildPlayer;
use rain1208\guildsAPI\utils\GuildPermission;

class GuildMenuForm extends AbstractMenuForm
{
    public function __construct(GuildPlayer $player)
    {
        $title = "参加するギルドの情報";
        $text = "";

        $options = [
            new MenuOption("情報"),
            new MenuOption("メンバー"),
            new MenuOption("ギルドから退出")
        ];

        if ($player->getPermission() === GuildPermission::admin ||
            $player->getPermission() === GuildPermission::OWNER) {
            $options[] = new MenuOption("プレイヤーの認証");
        }

        if ($player->getPermission() === GuildPermission::OWNER) {
            $options[] = new MenuOption("プレイヤーの設定");
            $options[] = new MenuOption("ギルドの削除");
        }

        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {

    }
}