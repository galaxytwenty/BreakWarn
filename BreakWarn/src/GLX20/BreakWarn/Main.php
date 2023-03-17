<?php
declare(strict_types=1);
namespace GLX20\BreakWarn;

use pocketmine\block\BlockToolType;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\Axe;
use pocketmine\item\Hoe;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\item\Tool;
use pocketmine\item\ToolTier;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

final class Main extends PluginBase implements Listener
{
    private $breakwarncfg;
    private $messages;
    private $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->breakwarncfg = new Config($this->getDataFolder()."BreakWarnToogle.yml");
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
        if($this->config->get("selectedLanguage") === "de"){
            $this->saveResource("de_DE.yml");
            $this->messages = new Config($this->getDataFolder() . "de_DE.yml", 2);
        }
        if($this->config->get("selectedLanguage") === "en"){
            $this->saveResource("en_EN.yml");
            $this->messages = new Config($this->getDataFolder() . "en_EN.yml", 2);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $player = $sender->getName();
        if($this->config->get("breakwarn_mode") === "command") {
            if ($command->getName() === "breakwarn") {
                if (!$sender->hasPermission("breakwarn.allow")) {
                    return false;
                }
                if (empty($args[0])) {
                    if (empty($this->breakwarncfg->get($player))) {
                        $this->breakwarncfg->set($player, "enabled");
                        $this->breakwarncfg->set("$player" . "_displayWarn", "popup");
                        $this->breakwarncfg->save();
                        $sender->sendMessage($this->messages->get("breakwarnEnable"));
                        $sender->sendMessage($this->messages->get("standartWarn"));
                        return false;
                    }
                    if ($this->breakwarncfg->get($player) === "enabled") {
                        $this->breakwarncfg->set($player, "disable");
                        $this->breakwarncfg->save();
                        $sender->sendMessage($this->messages->get("breakwarnDisable"));
                        return false;
                    }

                    if ($this->breakwarncfg->get($player) === "disable") {
                        $this->breakwarncfg->set($player, "enabled");
                        $this->breakwarncfg->save();
                        $sender->sendMessage($this->messages->get("breakwarnEnable"));
                        return false;
                    }
                } else {
                    if (isset($args[1])) {
                        $sender->sendMessage($this->messages->get("breakwarnUse"));
                        $sender->sendMessage($this->messages->get("breakwarnTypes"));
                        return false;
                    }
                    if ($args[0] === "chat") {
                        $this->breakwarncfg->set("$player" . "_displayWarn", "chat");
                        $sender->sendMessage($this->messages->get("typeChat"));
                        $this->breakwarncfg->save();
                        return false;
                    } elseif ($args[0] === "popup") {
                        $this->breakwarncfg->set("$player" . "_displayWarn", "popup");
                        $sender->sendMessage($this->messages->get("typePopup"));
                        $this->breakwarncfg->save();
                        return false;
                    } elseif ($args[0] === "screen") {
                        $this->breakwarncfg->set("$player" . "_displayWarn", "screen");
                        $sender->sendMessage($this->messages->get("typeScreen"));
                        $this->breakwarncfg->save();
                        return false;
                    } elseif ($args[0] === "all") {
                        $this->breakwarncfg->set("$player" . "_displayWarn", "all");
                        $sender->sendMessage($this->messages->get("typeAll"));
                        $this->breakwarncfg->save();
                        return false;
                    } elseif ($args[0] === "help") {
                        $sender->sendMessage($this->messages->get("breakwarnUse"));
                        $sender->sendMessage($this->messages->get("breakwarnTypes"));
                    } else {
                        $sender->sendMessage($this->messages->get("typeUnknown"));
                        return false;
                    }
                }

            }
        }elseif ($this->config->get("breakwarn_mode") === "tool"){
            if (!$sender->hasPermission("breakwarn.tool")) {
                return false;
            }

            if($command->getName() === "breakwarn" && $sender instanceof Player) {
                $item = $sender->getInventory()->getItemInHand();
                $namedTag = $item->getNamedTag();
                    if (isset($args[1])) {
                        $sender->sendMessage($this->messages->get("breakwarnUseTool"));
                        $sender->sendMessage($this->messages->get("breakwarnTypes"));
                        return false;
                    }
                    if ($args[0] === "chat") {
                        $value = "chat";
                    } elseif ($args[0] === "popup") {
                        $value = "popup";
                    } elseif ($args[0] === "screen") {
                        $value = "screen";
                    } elseif ($args[0] === "all") {
                        $value = "all";
                    } elseif ($args[0] === "remove") {
                        if ($namedTag->getTag("BreakWarn") !== null) {
                            $item->getNamedTag()->removeTag("BreakWarn");
                            $lore = $item->getLore();
                            foreach ($lore as $key => $value) {
                                if (strpos($value, "BreakWarn") !== false) {
                                    unset($lore[$key]);
                                }
                            }
                            $item->setLore(array_values($lore));
                            $sender->getInventory()->setItemInHand($item);
                            $sender->sendMessage($this->messages->get("removeNbt"));
                            return false;
                        } else {
                            $sender->sendMessage($this->messages->get("notExistNbt"));
                            return false;
                        }
                    } elseif ($args[0] === "help") {
                        $sender->sendMessage($this->messages->get("breakwarnUseTool"));
                        $sender->sendMessage($this->messages->get("breakwarnTypes"));
                        return false;
                    } else {
                        $sender->sendMessage($this->messages->get("typeUnknown"));
                        return false;
                    }
                }

                if ($namedTag !== null && $namedTag->getTag("BreakWarn") !== null){
                    $sender->sendMessage($this->messages->get("existNbt"));
                    return false;
                }
                $name = "BreakWarn";
                $lore = $item->getLore();
                $lore[] = "BreakWarn (". $args[0] .")";
                $item->setLore($lore);
                $item->getNamedTag()->setString($name, $value);
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage($this->messages->get("createNbt"));

                return true;
            }
        }

    public function onBlockBreak(BlockBreakEvent $event) : void
    {
        $player = $event->getPlayer();
        $playerName = $event->getPlayer()->getName();
        $handItem = $player->getInventory()->getItemInHand();
        $handMeta = $player->getInventory()->getItemInHand()->getMeta();
        $namedTag = $handItem->getNamedTag();
        if ($this->breakwarncfg->get($playerName) === "disable") {
            return;
        }
        if ($this->config->get("breakwarn_mode") === "tool") {
            if ($namedTag !== null && $namedTag->getTag("BreakWarn") !== null) {
                if ($handMeta < (0.9 * $event->getItem()->getMaxDurability())) return;
                $this->sendThingy($player, $event->getItem());
            }
        }
        if ($this->config->get("breakwarn_mode") === "command") {
            if ($handMeta < (0.9 * $event->getItem()->getMaxDurability())) return;
            $this->sendThingy($player, $event->getItem());
        }
    }

    public function onAtack(EntityDamageByEntityEvent $event) : void
    {
        $player = $event->getDamager();
        $handItem = $player->getInventory()->getItemInHand();
        $namedTag = $handItem->getNamedTag();
        $handMeta = $player->getInventory()->getItemInHand()->getMeta();
        if (!$player instanceof Player) return;

        $playerName = $player->getName();
        if ($this->breakwarncfg->get($playerName) === "disable") {
            return;
        }
        if ($namedTag !== null && $namedTag->getTag("BreakWarn") !== null){
            if($player->getInventory()->getItemInHand()->getMeta() < (0.9 * $event->getItem()->getMaxDurability())) return;
            $this->sendThingy($player, $handItem);
        }

        if($player->getInventory()->getItemInHand()->getMeta() < (0.9 * $event->getItem()->getMaxDurability())) return;
        $this->sendThingy($player, $handItem);
    }

    private function sendThingy(Player $player, $item) : void {
        $playerName = $player->getName();
        $tierName = match($player->getInventory()->getItemInHand()->getTier()) {
            ToolTier::WOOD() => "wooden",
            ToolTier::STONE() => "stone",
            ToolTier::IRON() => "iron",
            ToolTier::GOLD() => "golden",
            ToolTier::DIAMOND() => "diamond"
        };
        if ($item instanceof Pickaxe) {
            if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat" or $item->getNamedTag()->getString("BreakWarn") === "chat") {
                $player->sendMessage($this->messages->get("{$tierName}PickaxeChat"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup" or $item->getNamedTag()->getString("BreakWarn") === "popup") {
                $player->sendActionBarMessage($this->messages->get("{$tierName}PickaxePopup"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen" or $item->getNamedTag()->getString("BreakWarn") === "screen") {
                $player->sendTitle($this->messages->get("{$tierName}PickaxeScreen"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all" or $item->getNamedTag()->getString("BreakWarn") === "all") {
                $player->sendMessage($this->messages->get("{$tierName}PickaxeChat"));
                $player->sendActionBarMessage($this->messages->get("{$tierName}PickaxePopup"));
                $player->sendTitle($this->messages->get("{$tierName}PickaxeScreen"));
            }
        }elseif($item instanceof Shovel) {
            if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat" or $item->getNamedTag()->getString("BreakWarn") === "chat") {
                $player->sendMessage($this->messages->get("{$tierName}ShovelChat"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup" or $item->getNamedTag()->getString("BreakWarn") === "popup") {
                $player->sendActionBarMessage($this->messages->get("{$tierName}ShovelPopup"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen" or $item->getNamedTag()->getString("BreakWarn") === "screen") {
                $player->sendTitle($this->messages->get("{$tierName}ShovelScreen"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all" or $item->getNamedTag()->getString("BreakWarn") === "all") {
                $player->sendMessage($this->messages->get("{$tierName}ShovelChat"));
                $player->sendActionBarMessage($this->messages->get("{$tierName}ShovelPopup"));
                $player->sendTitle($this->messages->get("{$tierName}ShovelScreen"));
            }
        }elseif($item instanceof Hoe) {
            if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat" or $item->getNamedTag()->getString("BreakWarn") === "chat") {
                $player->sendMessage($this->messages->get("{$tierName}HoeChat"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup" or $item->getNamedTag()->getString("BreakWarn") === "popup") {
                $player->sendActionBarMessage($this->messages->get("{$tierName}HoePopup"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen" or $item->getNamedTag()->getString("BreakWarn") === "screen") {
                $player->sendTitle($this->messages->get("{$tierName}HoeScreen"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all" or $item->getNamedTag()->getString("BreakWarn") === "all") {
                $player->sendMessage($this->messages->get("{$tierName}HoeChat"));
                $player->sendActionBarMessage($this->messages->get("{$tierName}HoePopup"));
                $player->sendTitle($this->messages->get("{$tierName}HoeScreen"));
            }
        }elseif($item instanceof Axe) {
            if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat" or $item->getNamedTag()->getString("BreakWarn") === "chat") {
                $player->sendMessage($this->messages->get("{$tierName}AxeChat"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup" or $item->getNamedTag()->getString("BreakWarn") === "popup") {
                $player->sendActionBarMessage($this->messages->get("{$tierName}AxePopup"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen" or $item->getNamedTag()->getString("BreakWarn") === "screen") {
                $player->sendTitle($this->messages->get("{$tierName}AxeScreen"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all" or $item->getNamedTag()->getString("BreakWarn") === "all") {
                $player->sendMessage($this->messages->get("{$tierName}AxeChat"));
                $player->sendActionBarMessage($this->messages->get("{$tierName}AxePopup"));
                $player->sendTitle($this->messages->get("{$tierName}AxeScreen"));
            }
        }elseif($item instanceof Sword) {
            if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat" or $item->getNamedTag()->getString("BreakWarn") === "chat") {
                $player->sendMessage($this->messages->get("{$tierName}SwordChat"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup" or $item->getNamedTag()->getString("BreakWarn") === "popup") {
                $player->sendActionBarMessage($this->messages->get("{$tierName}SwordPopup"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen" or $item->getNamedTag()->getString("BreakWarn") === "screen") {
                $player->sendTitle($this->messages->get("{$tierName}SwordScreen"));
            } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all" or $item->getNamedTag()->getString("BreakWarn") === "all") {
                $player->sendMessage($this->messages->get("{$tierName}SwordChat"));
                $player->sendActionBarMessage($this->messages->get("{$tierName}SwordPopup"));
                $player->sendTitle($this->messages->get("{$tierName}SwordScreen"));
            }
        }
    }
}
