<?php


namespace rain1208\guildsAPI\forms\guilds\join;


use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;
use rain1208\guildsAPI\forms\ErrorForm;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\models\GuildId;

class JoinForm extends AbstractModalForm
{
    private Guild $guild;
    private Form $back;

    public function __construct(Guild $guild, Form $back)
    {
        $this->guild = $guild;
        $this->back = $back;

        $title = "ギルドへの参加申請";
        $text = $guild->getGuildInfoString();
        $yesButtonText = "参加申請";
        $noButtonText = "戻る";
        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    public function submit(Player $player, bool $bool): void
    {
        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());

        if ($bool) {
            if ($guildPlayer->getGuildId()->getValue() !== GuildId::NO_GUILD) {
                $player->sendForm(new ErrorForm("あなたはすでにギルドに参加しています", $this));
                return;
            }

            $this->guild->join($guildPlayer);
            return;
        }
        $player->sendForm($this->back);
    }
}