<?php


namespace rain1208\guildsAPI\forms\guilds\memberList;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\GuildMenuForm;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\Main;

class ByPermissionMemberListForm extends AbstractMenuForm
{
    private Guild $guild;

    public function __construct(Guild $guild)
    {
        $this->guild = $guild;

        $title = "メンバーリスト";
        $text = "";
        $options = [
            new MenuOption("オーナー"),
            new MenuOption("管理者"),
            new MenuOption("メンバー"),
            new MenuOption("認証待ち"),
            new MenuOption("戻る")
        ];

        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        if ($select === 4) {
            $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());
            $player->sendForm(new GuildMenuForm($guildPlayer));
            return;
        }

        $data = Main::getInstance()->getDatabase()->getGuildMember($this->guild->getGuildId());

        $player->sendForm(new MemberListForm($data[$select], $this));
    }
}