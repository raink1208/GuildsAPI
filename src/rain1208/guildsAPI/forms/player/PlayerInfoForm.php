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

        $title = "";
        $text = "";
        $yesButtonText = "";
        $noButtonText = "";

        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    public function submit(Player $player, bool $bool): void
    {
        if ($bool) {
            if ($this->back === null) return;
            $player->sendForm($this->back);
        }
    }
}