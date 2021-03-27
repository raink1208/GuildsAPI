<?php


namespace rain1208\guildsAPI\forms\lists;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;
use rain1208\guildsAPI\forms\MainForm;
use rain1208\guildsAPI\Main;

class MoneySortedList extends AbstractMenuForm
{
    private array $guilds;

    public function __construct()
    {
        $title = "";
        $text = "";
        $options = [];

        $data = Main::getInstance()->getGuildManager()->getMoneySortGuilds();

        for ($i = 1; $i < count($data); $i+=10) {
            $options[] = new MenuOption($i . " ~ " . ($i + 9));
        }

        $options[] = new MenuOption("戻る");

        $this->guilds = $this->delimit($data);

        parent::__construct($title, $text, $options);
    }

    private function delimit(array $guilds): array
    {
        return array_chunk($guilds, 10);
    }

    public function submit(Player $player, int $select): void
    {
        if (count($this->guilds) === $select) {
            $player->sendForm(new MainForm());
            return;
        }

        $player->sendForm(new GuildListForm($this->guilds[$select]));
    }
}