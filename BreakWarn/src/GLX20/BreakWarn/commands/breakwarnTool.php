<?php
declare(strict_types=1);
namespace GLX20\BreakWarn\commands;

use GLX20\BreakWarn\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class breakwarnTool extends Command implements PluginOwned
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("breakwarn", "Warn player before tool get breaks", "§a/breakwarn §6<§bWarnArt§6> §6<§bremove§6>");
        $this->plugin = $plugin;
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!($sender instanceof Player)) {
            return false;
        }
        if (!$sender->hasPermission("breakwarn.tool")) {
            return false;
        }

        $item = $sender->getInventory()->getItemInHand();
        $value = "chat";

        if (isset($args[1])) {
            $sender->sendMessage($this->plugin->messages->get("breakwarnUseTool"));
            $sender->sendMessage($this->plugin->messages->get("breakwarnTypes"));
            return false;
        }

        if (!isset($args[0])) {
            $sender->sendMessage($this->plugin->messages->get("typeUnknown"));
            return false;
        }

        if (!in_array($args[0], ["chat", "popup", "screen", "all", "remove", "help"])) {
            $sender->sendMessage($this->plugin->messages->get("breakwarnUseTool"));
            $sender->sendMessage($this->plugin->messages->get("breakwarnTypes"));
            return false;
        }

        if ($args[0] == "remove") {
            if ($item->getNamedTag()->getTag("BreakWarn") != null) {
                $item->getNamedTag()->removeTag("BreakWarn");
                $lore = $item->getLore();
                foreach ($lore as $key => $value) {
                    if (str_contains($value, "BreakWarn")) {
                        unset($lore[$key]);
                    }
                }
                $item->setLore(array_values($lore));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage($this->plugin->messages->get("removeNbt"));
            } else {
                $sender->sendMessage($this->plugin->messages->get("notExistNbt"));
            }
            return true;
        }

        if ($args[0] == "help") {
            $sender->sendMessage($this->plugin->messages->get("breakwarnUseTool"));
            $sender->sendMessage($this->plugin->messages->get("breakwarnTypes"));
            return true;
        }

        if ($args[0] == "chat") {
            $value = "chat";
        } elseif ($args[0] == "popup") {
            $value = "popup";
        } elseif ($args[0] == "screen") {
            $value = "screen";
        } elseif ($args[0] == "all") {
            $value = "all";
        }

        if ($item->getNamedTag()->getTag("BreakWarn") != null) {
            $sender->sendMessage($this->plugin->messages->get("existNbt"));
            return false;
        }

        if (!$item instanceof Tool) {
           $sender->sendMessage($this->plugin->messages->get("onlyOnTool"));
            return false;
        }

        $name = "BreakWarn";
        $lore = $item->getLore();
        $lore[] = "BreakWarn (" . $args[0] . ")";
        $item->setLore($lore);




        $item->getNamedTag()->setString($name, $value);
        $item->addEnchantment($this->plugin->getEnchantment());
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage($this->plugin->messages->get("createNbt"));
        return true;
    }


    public function getOwningPlugin(): Plugin
    {
        return $this->plugin;
    }
}
