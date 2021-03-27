<?php


namespace rain1208\guildsAPI\forms\guilds;


use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use pocketmine\Player;
use rain1208\guildsAPI\forms\addons\AbstractCustomForm;
use rain1208\guildsAPI\forms\lists\GuildListForm;
use rain1208\guildsAPI\Main;
use rain1208\guildsAPI\utils\StringUtil;

class GuildSearchForm extends AbstractCustomForm
{
    private bool $join;

    public function __construct(bool $join = false)
    {
        $this->join = $join;

        $title = "Guildの検索";
        $elements = [
            new Label("label1","各項目でマッチしたものを抽出します(OR検索)"),
            new Input("guild_Id", "GuildIDで検索","GuildID"),
            new Input("name", "Guildの名前で検索", "GuildName"),
            new Input("owner", "オーナーのプレイヤーで検索", "OwnerName")
        ];
        parent::__construct($title, $elements);
    }

    public function submit(Player $player, CustomFormResponse $response): void
    {
        $result = [];
        $guilds = Main::getInstance()->getGuildManager()->getGuilds();

        $guildId = $response->getString("guild_Id");
        $name = $response->getString("name");
        $owner = $response->getString("owner");

        foreach ($guilds as $guild) {
            if ($guild->getGuildId()->getValue() === intval($guildId)) {
                $result[] = $guild;
                continue;
            }
            if (StringUtil::startWithIgnoreCase($guild->getName(), $name) && $name !== "") {
                $result[] = $guild;
                continue;
            }
            if ($guild->getOwnerName() === $owner) {
                $result[] = $guild;
                continue;
            }
        }

        $player->sendForm(new GuildListForm($result, $this, $this->join));
    }
}