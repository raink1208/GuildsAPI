<?php


namespace rain1208\guildsAPI\forms\guilds\auth;


use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;
use rain1208\guildsAPI\forms\ErrorForm;
use rain1208\guildsAPI\forms\guilds\GuildMenuForm;
use rain1208\guildsAPI\forms\guilds\memberList\MemberListForm;
use rain1208\guildsAPI\guilds\GuildPlayer;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\utils\GuildPermission;

class PlayerAuthenticationForm extends AbstractModalForm
{
    private GuildPlayer $selected;
    private Form $back;

    public function __construct(GuildPlayer $player, Form $back)
    {
        $this->selected = $player;
        $this->back = $back;

        $title = "プレイヤーの認証";
        $text = $player->getInfoString();
        $yesButtonText = "認証";
        $noButtonText = "拒否";
        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    public function submit(Player $player, bool $bool): void
    {
        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());

        if (!$guildPlayer->getGuildId()->equals($this->selected->getGuildId())) {
            $player->sendForm(new ErrorForm("GuildIDが一致しませんでした"));
            return;
        }

        $guild = Main::getInstance()->getGuildManager()->getGuild($this->selected->getGuildId());

        if ($guildPlayer->getPermission() === GuildPermission::OWNER ||
            $guildPlayer->getPermission() === GuildPermission::admin) {
            if ($bool) {
                $guild->accept($this->selected);
            } else {
                $guild->leave($this->selected);
            }
        }

        $player->sendForm(new MemberListForm($guild->getWait(), new GuildMenuForm($guildPlayer), true));
    }
}