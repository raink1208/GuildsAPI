<?php


namespace rain1208\guildsAPI\forms\guilds\join;


use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;
use rain1208\guildsAPI\guilds\Guild;

class JoinForm extends AbstractModalForm
{
    private Guild $guild;

    public function __construct(Guild $guild)
    {
        $this->guild = $guild;

        $title = "";
        $text = "";
        $yesButtonText = "";
        $noButtonText = "";
        parent::__construct($title, $text, $yesButtonText, $noButtonText);
    }

    public function submit(Player $player, bool $bool): void
    {
        // TODO: Implement submit() method.
    }
}