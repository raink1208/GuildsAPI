<?php


namespace rain1208\guildsAPI\forms\guilds\delete;


use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;
use rain1208\guildsAPI\forms\ErrorForm;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\Main;

class GuildDeleteForm extends AbstractCustomForm
{
    private Guild $guild;

    public function __construct(Guild $guild)
    {
        $this->guild = $guild;

        $title = "";

        $elements = [
            new Label("label", $guild->getGuildInfoString()),
            new Input("Name", "ギルド名を入力してください(誤削除防止のため)")
        ];

        parent::__construct($title, $elements);
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {
        $inputName = $response->getString("Name");
        $guildPlayer = Main::getInstance()->getGuildPlayerManager()->getGuildPlayer($player->getName());

        if (!$guildPlayer->getGuildId()->equals($this->guild->getGuildId())) {
            $player->sendForm(new ErrorForm(""));
            return;
        }

        if ($this->guild->getName() !== $inputName) {
            $player->sendForm(new ErrorForm("入力したギルド名が一致しませんでした", $this));
            return;
        }

        Main::getInstance()->getGuildManager()->deleteGuild($this->guild);
        $player->sendMessage($this->guild->getName()."を削除しました");
    }
}