<?php


namespace rain1208\guildsAPI\forms\guilds\create;


use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;
use rain1208\guildsAPI\forms\ErrorForm;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\wrapper\EconomyPlugin;

class GuildCreateForm extends AbstractCustomForm
{
    public function __construct()
    {
        $title = "";
        $elements = [
            new Input("name", "作成するギルドの名前"),
            new Label("label1", "ギルドの作成には".Main::getInstance()->getGuildManager()->getGuildCreateNeedMoney()."円必要です")
        ];

        parent::__construct($title, $elements);
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {
        $name = $response->getString("name");
        $guildManager = Main::getInstance()->getGuildManager();

        if ($guildManager->nameExists($name)) {
            $player->sendForm(new ErrorForm("既にその名前のギルドが存在します", $this));
            return;
        }

        if (strpos($name, "§") !== false) {
            $player->sendForm(new ErrorForm("ギルド名に使えない文字が入っています", $this));
            return;
        }

        if (!EconomyPlugin::hasEnoughMoney($player, Main::getInstance()->getGuildManager()->getGuildCreateNeedMoney())) {
            $player->sendForm(new ErrorForm("ギルド作成に必要なお金がありません"));
            return;
        }

        $player->sendForm(new GuildCreateConfirmForm($name));
    }
}