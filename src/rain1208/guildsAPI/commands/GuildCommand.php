<?php


namespace rain1208\guildsAPI\commands;


use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use rain1208\guildsAPI\forms\MainForm;

class GuildCommand extends PluginCommand implements CommandExecutor
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setDescription("ギルドのメインフォームを開く");

        $this->setExecutor($this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!($sender instanceof Player)) return true;

        $sender->sendForm(new MainForm());

        return true;
    }
}