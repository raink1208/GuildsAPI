<?php


namespace rain1208\guildsAPI\forms;


use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractModalForm;

class ErrorForm extends AbstractModalForm
{
    private ?Form $back;

    public function __construct(string $text,?Form $back = null)
    {
        $this->back = $back;

        $title = "Error Form";
        $yesButtonText = "戻る";
        $noButtonText = "やめる";
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