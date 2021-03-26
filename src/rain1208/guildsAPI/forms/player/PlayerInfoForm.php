<?php


namespace rain1208\guildsAPI\forms\player;


use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;
use rain1208\guildsAPI\guilds\GuildPlayer;

class PlayerInfoForm extends AbstractModalForm
{
    private ?Form $back;

    public function __construct(GuildPlayer $player, Form $back = null)
    {
        $this->back = $back;

        $title = $player->getName()."の情報";
        $text = $this->getInfo($player);

        $yesButtonText = "戻る";
        $noButtonText = "やめる";

        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    private function getInfo(GuildPlayer $player): string
    {
        $data = $player->getInfo();

        $msg  = "プレイヤーのID: " . $data["name"] . "\n";
        $msg .= "所持金: " . $data["money"] . "\n";
        $msg .= "参加中のギルド: " . $data["guild"] . " ギルドID: " . $data["guildId"] . "\n";
        $msg .= "ギルドの権限: " . $data["permission"];

        return $msg;
    }

    public function submit(Player $player, bool $bool): void
    {
        if ($bool) {
            if ($this->back === null) return;
            $player->sendForm($this->back);
        }
    }
}