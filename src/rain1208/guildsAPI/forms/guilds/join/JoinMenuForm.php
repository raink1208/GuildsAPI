<?php


namespace rain1208\guildsAPI\forms\guilds\join;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\guilds\GuildSearchForm;
use rain1208\guildsAPI\forms\lists\MoneySortedList;
use rain1208\guildsAPI\forms\MainForm;

class JoinMenuForm extends AbstractMenuForm
{
    public function __construct()
    {
        $title = "Join Guild Menu";
        $text = "";
        $options = [
            new MenuOption("検索"),
            new MenuOption("所持金ランキングから選ぶ")
        ];

        $options[] = new MenuOption("戻る");
        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
        if ($this->getOption($select)->getText() === "戻る") {
            $player->sendForm(new MainForm());
            return;
        }

        switch ($select) {
            case 0:
                $player->sendForm(new GuildSearchForm(true));
                break;
            case 1:
                $player->sendForm(new MoneySortedList(true));
                break;
        }
    }
}