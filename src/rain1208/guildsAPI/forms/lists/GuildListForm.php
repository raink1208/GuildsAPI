<?php


namespace rain1208\guildsAPI\forms\lists;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\GuildInfoForm;
use rain1208\guildsAPI\guilds\Guild;

class GuildListForm extends AbstractMenuForm
{
    private array $guilds;

    /** @param Guild[] $guilds */
    public function __construct(array $guilds)
    {
        $title = "";
        $text = "";
        $options = [];

        foreach ($guilds as $guild) {
            $options[] = new MenuOption($guild->getName());
        }

        $this->guilds = $guilds;

        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        $player->sendForm(new GuildInfoForm($this->guilds[$select], $this));
    }
}