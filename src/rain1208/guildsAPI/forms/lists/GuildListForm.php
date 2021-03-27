<?php


namespace rain1208\guildsAPI\forms\lists;


use dktapps\pmforms\MenuOption;
use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\GuildInfoForm;
use rain1208\guildsAPI\guilds\Guild;

class GuildListForm extends AbstractMenuForm
{
    private array $guilds;
    private ?Form $back;

    /**
     * @param Guild[] $guilds
     * @param ?Form $back
     */
    public function __construct(array $guilds, Form $back = null)
    {
        $this->back = $back;

        $title = "Guild List Form";
        $text = "";
        $options = [];

        foreach ($guilds as $guild) {
            $options[] = new MenuOption($guild->getName());
        }

        $options[] = new MenuOption("戻る");

        $this->guilds = $guilds;

        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        if ($this->getOption($select)->getText() === "戻る") {
            if ($this->back === null) return;
            $player->sendForm($this->back);
        }

        $player->sendForm(new GuildInfoForm($this->guilds[$select], $this));
    }
}