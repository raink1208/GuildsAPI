<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\memberList\ByPermissionMemberListForm;
use rain1208\guildsAPI\forms\MainForm;
use rain1208\guildsAPI\guilds\GuildPlayer;
use rain1208\guildsAPI\Main;
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

        $options[] = new MenuOption("戻る");

        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        if ($this->getOption($select)->getText() === "戻る") {
            $player->sendForm(new MainForm());
            return;
        }

        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());
        $guild = Main::getInstance()->getGuildManager()->getGuild($guildPlayer->getGuildId());

        if ($guild === null) return;

        switch ($select) {
            case 0:
                $player->sendForm(new GuildInfoForm($guild, $this));
                break;
            case 1:
                $player->sendForm(new ByPermissionMemberListForm($guild));
                break;
        }
    }
}