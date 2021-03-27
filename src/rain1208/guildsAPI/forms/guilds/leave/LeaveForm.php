<?php


namespace rain1208\guildsAPI\forms\guilds\leave;


use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;
use rain1208\guildsAPI\forms\ErrorForm;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\utils\GuildPermission;

class LeaveForm extends AbstractModalForm
{
    private Guild $guild;

    public function __construct(Guild $guild)
    {
        $this->guild = $guild;

        $title = "ギルドから退出";
        $text = $guild->getGuildInfoString();
        $yesButtonText = "退出する";
        $noButtonText = "やめる";
        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    public function submit(Player $player, bool $bool): void
    {
        if ($bool === false) return;

        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());

        if (!$this->guild->getGuildId()->equals($guildPlayer->getGuildId())) {
            $player->sendForm(new ErrorForm("GuildIDが一致しませんでした\nこのエラーが出た場合は開発者に報告してください", null));
            return;
        }

        if ($guildPlayer->getPermission() === GuildPermission::OWNER) {
            $player->sendForm(new ErrorForm("あなたはギルドのオーナーのため退出できません"));
            return;
        }

        $this->guild->leave($guildPlayer);
        $player->sendMessage($this->guild->getName()."から退出しました");
    }
}