<?php


namespace rain1208\guildsAPI\forms\guilds\create;


use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\wrapper\EconomyPlugin;

class GuildCreateConfirmForm extends AbstractModalForm
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;

        $title = "ギルド作成確認";
        $text  = "ギルド名: ".$name."\nでギルドを作成します";
        $text .= "ギルド作成に必要な金額 ". Main::getInstance()->getGuildManager()->getGuildCreateNeedMoney()."円";
        $yesButtonText = "作成する";
        $noButtonText = "やめる";
        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    public function submit(Player $player, bool $bool): void
    {
        if ($bool) {
            EconomyPlugin::reduceMoney($player, Main::getInstance()->getGuildManager()->getGuildCreateNeedMoney());

            $guild = Main::getInstance()->getGuildManager()->createGuild($this->name, $player->getName());
            $player->sendForm(new GuildCreateCompleteForm($guild));
        }
    }
}