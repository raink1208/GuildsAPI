<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\ErrorForm;
use rain1208\guildsAPI\forms\guilds\delete\GuildDeleteForm;
use rain1208\guildsAPI\forms\guilds\leave\LeaveForm;
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

        switch ($this->getOption($select)->getText()) {
            case "情報":
                $player->sendForm(new GuildInfoForm($guild, $this));
                break;
            case "メンバー":
                $player->sendForm(new ByPermissionMemberListForm($guild));
                break;
            case "ギルドから退出":
                $player->sendForm(new LeaveForm($guild));
                break;
            case "プレイヤーの認証":
                $player->sendForm();//
                break;
            case "プレイヤーの設定":
                $player->sendForm();
                break;
            case "ギルドの削除":
                if ($guildPlayer->getPermission() !== GuildPermission::OWNER) {
                    $player->sendForm(new ErrorForm("あなたはオーナーではないためこの機能は使えません"));
                    return;
                }
                $player->sendForm(new GuildDeleteForm($guild));
                break;
        }
    }
}