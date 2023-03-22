<?php
declare(strict_types=1);
namespace GLX20\BreakWarn\commands;

use GLX20\BreakWarn\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class breakwarnCommand extends Command implements PluginOwned
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("breakwarn", "Warn player before tool get breaks", "§a/breakwarn §6<§bWarnArt§6> §6<§bremove§6>");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $playerName = $sender->getName();
        if (!($sender instanceof Player)) {
            return false;
        }
        if (!$sender->hasPermission("breakwarn.allow")) {
            return false;
        }
        if (empty($args[0])) {
            if (empty($this->plugin->breakwarncfg->get($playerName))) {
                $this->plugin->breakwarncfg->set($playerName, "enabled");
                $this->plugin->breakwarncfg->set("$playerName" . "_displayWarn", "popup");
                $this->plugin->breakwarncfg->save();
                $sender->sendMessage($this->plugin->messages->get("breakwarnEnable"));
                $sender->sendMessage($this->plugin->messages->get("standartWarn"));
                return false;
            }
            if ($this->plugin->breakwarncfg->get($playerName) === "enabled") {
                $newValue = "disable";
                $message = "breakwarnDisable";
            } else {
                $newValue = "enabled";
                $message = "breakwarnEnable";
            }
            $this->plugin->breakwarncfg->set($playerName, $newValue);
            $this->plugin->breakwarncfg->save();
            $sender->sendMessage($this->plugin->messages->get($message));
            return false;
        } else {
            if (isset($args[1])) {
                $sender->sendMessage($this->plugin->messages->get("breakwarnUse"));
                $sender->sendMessage($this->plugin->messages->get("breakwarnTypes"));
                return false;
            }
            if ($args[0] === "chat") {
                $this->plugin->breakwarncfg->set("$playerName" . "_displayWarn", "chat");
                $sender->sendMessage($this->plugin->messages->get("typeChat"));
            } else if ($args[0] === "popup") {
                $this->plugin->breakwarncfg->set("$playerName" . "_displayWarn", "popup");
                $sender->sendMessage($this->plugin->messages->get("typePopup"));
            } else if ($args[0] === "screen") {
                $this->plugin->breakwarncfg->set("$playerName" . "_displayWarn", "screen");
                $sender->sendMessage($this->plugin->messages->get("typeScreen"));
            } else if ($args[0] === "all") {
                $this->plugin->breakwarncfg->set("$playerName" . "_displayWarn", "all");
                $sender->sendMessage($this->plugin->messages->get("typeAll"));
            } else if ($args[0] === "help") {
                $sender->sendMessage($this->plugin->messages->get("breakwarnUse"));
                $sender->sendMessage($this->plugin->messages->get("breakwarnTypes"));
            } else {
                $sender->sendMessage($this->plugin->messages->get("typeUnknown"));
            }
            $this->plugin->breakwarncfg->save();
            return false;
        }
    }


    public function getOwningPlugin(): Plugin
    {
        return $this->plugin;
    }
}
