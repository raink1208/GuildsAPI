<?php


namespace rain1208\guildsAPI\forms\guilds\memberList;


use dktapps\pmforms\MenuOption;
use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\player\PlayerInfoForm;
use rain1208\guildsAPI\Main;

class MemberListForm extends AbstractMenuForm
{

    private array $players;
    private ?Form $back;

    /**
     * @param string[] $guildPlayers
     * @param ?Form $back
     */
    public function __construct(array $guildPlayers, Form $back = null)
    {
        $this->players = $guildPlayers;
        $this->back = $back;

        $title = "";
        $text = "";
        $options = [];

        foreach ($guildPlayers as $guildPlayer) {
            $options[] = new MenuOption($guildPlayer);
        }

        $options[] = new MenuOption("戻る");

        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        if (count($this->players) === $select) {
            if ($this->back === null) return;
            $player->sendForm($this->back);
            return;
        }

        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($this->players[$select]);

        if ($guildPlayer === null) return;

        $player->sendForm(new PlayerInfoForm($guildPlayer, $this));
    }
}