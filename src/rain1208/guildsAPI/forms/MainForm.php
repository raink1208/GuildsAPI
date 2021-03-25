<?php


namespace rain1208\guildsAPI\forms;


use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractMenuForm;

class MainForm extends AbstractMenuForm
{
    public function __construct()
    {
        $title = "Guild Main Form";
        $text = "";
        $options = [
            new MenuOption("参加しているギルドの確認"),
            new MenuOption("ギルドに参加"),
            new MenuOption("ギルドの一覧"),
            new MenuOption("所持金ランキング"),
            new MenuOption("ギルドの作成")
        ];
        parent::__construct($title, $text, $options);
    }

    public function submit(Player $player, int $select): void
    {
    }
}