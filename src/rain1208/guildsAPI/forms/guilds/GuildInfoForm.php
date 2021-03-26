<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Label;
use pocketmine\form\Form;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;
use rain1208\guildsAPI\guilds\Guild;
use rain1208\guildsAPI\utils\GuildPermission;

class GuildInfoForm extends AbstractCustomForm
{
    private Form $back;

    public function __construct(Guild $guild, Form $back)
    {
        $this->back = $back;

        $title = $guild->getName()."の情報";
        $elements = [
            new Label("guildInfo", $this->getInfo($guild))
        ];
        parent::__construct($title, $elements);
    }

    private function getInfo(Guild $guild): string
    {
        $info = $guild->getGuildInfo();

        $msg  = "ギルド名: " . $info["name"] . "\n";
        $msg .= "ギルドのオーナー: " . $info["owner"] . "\n";
        $msg .= "所持金合計: " . $info["totalMoney"]."\n";
        $msg .= "ギルドのレベル(exp): ". $info["level"]."(".$info["exp"].")\n\n";
        $msg .= "ギルドメンバー情報" . "\n";
        $msg .= "管理者数: ".$info["memberCount"][GuildPermission::admin] . "\n";
        $msg .= "メンバー数: ".$info["memberCount"][GuildPermission::member] . "\n";
        $msg .= "認証待ち数: ".$info["memberCount"][GuildPermission::wait];

        return $msg;
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {
        $player->sendForm($this->back);
    }
}