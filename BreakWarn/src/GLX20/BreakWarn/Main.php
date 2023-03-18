<?php
declare(strict_types=1);
namespace GLX20\BreakWarn;

use falkirks\minereset\exception\InvalidStateException;
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
        $selectedLanguage = $this->config->get("selectedLanguage");
        if ($selectedLanguage === "de") {
            $this->loadLanguageFile("de_DE");
        } elseif ($selectedLanguage === "en") {
            $this->loadLanguageFile("en_EN");
        }
    }

    private function loadLanguageFile(string $langCode) : void {
        $this->saveResource($langCode . ".yml");
        $this->messages = new Config($this->getDataFolder() . $langCode . ".yml", 2);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $player = $sender->getName();
        if (!($sender instanceof Player)) return false;
        if($this->config->get("breakwarn_mode") === "command") {
            if ($command->getName() === "breakwarn") {
                if (!$sender->hasPermission("breakwarn.allow")) return false;
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
                        $newValue = "disable";
                        $message = "breakwarnDisable";
                    }else{
                        $newValue = "enabled";
                        $message = "breakwarnEnable";
                    }
                    $this->breakwarncfg->set($player, $newValue);
                    $this->breakwarncfg->save();
                    $sender->sendMessage($this->messages->get($message));
                    return false;
                }else{
                    if (isset($args[1])) {
                        $sender->sendMessage($this->messages->get("breakwarnUse"));
                        $sender->sendMessage($this->messages->get("breakwarnTypes"));
                        return false;
                    }
                    switch ($args[0]) {
                        case "chat":
                            $this->breakwarncfg->set("$player" . "_displayWarn", "chat");
                            $sender->sendMessage($this->messages->get("typeChat"));
                            break;
                        case "popup":
                            $this->breakwarncfg->set("$player" . "_displayWarn", "popup");
                            $sender->sendMessage($this->messages->get("typePopup"));
                            break;
                        case "screen":
                            $this->breakwarncfg->set("$player" . "_displayWarn", "screen");
                            $sender->sendMessage($this->messages->get("typeScreen"));
                            break;
                        case "all":
                            $this->breakwarncfg->set("$player" . "_displayWarn", "all");
                            $sender->sendMessage($this->messages->get("typeAll"));
                            break;
                        case "help":
                            $sender->sendMessage($this->messages->get("breakwarnUse"));
                            $sender->sendMessage($this->messages->get("breakwarnTypes"));
                            break;
                        default:
                            $sender->sendMessage($this->messages->get("typeUnknown"));
                            break;
                    }
                    $this->breakwarncfg->save();
                    return false;
                }
            }
        }elseif ($this->config->get("breakwarn_mode") === "tool"){
            if (!$sender->hasPermission("breakwarn.tool")) return false;
            if($command->getName() === "breakwarn") {
                $item = $sender->getInventory()->getItemInHand();
                if (isset($args[1])) {
                    $sender->sendMessage($this->messages->get("breakwarnUseTool"));
                    $sender->sendMessage($this->messages->get("breakwarnTypes"));
                    return false;
                }
                switch ($args[0]) {
                    case "chat":
                        $value = "chat";
                        break;
                    case "popup":
                        $value = "popup";
                        break;
                    case "screen":
                        $value = "screen";
                        break;
                    case "all":
                        $value = "all";
                        break;
                    case "remove":
                        if ($item->getNamedTag()->getTag("BreakWarn") !== null) {
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
                        } else {
                            $sender->sendMessage($this->messages->get("notExistNbt"));
                        }
                        break;
                    case "help":
                        $sender->sendMessage($this->messages->get("breakwarnUseTool"));
                        $sender->sendMessage($this->messages->get("breakwarnTypes"));
                        break;
                    default:
                        $sender->sendMessage($this->messages->get("typeUnknown"));
                        break;
                }

            }
            if ($item->getNamedTag()->getTag("BreakWarn") !== null){
                        $sender->sendMessage($this->messages->get("existNbt"));
                        return false;
                    }
            $name = "BreakWarn";
            $lore = $item->getLore();
            $lore[] = "BreakWarn (". $args[0] .")";
            $item->setLore($lore);
            $item->getNamedTag()->setString($name, $value);
            $player->getInventory()->setItemInHand($item);
            $sender->sendMessage($this->messages->get("createNbt"));
        }
        return true;
    }

    public function onBlockBreak(BlockBreakEvent $event) : void
    {
        $player = $event->getPlayer();
        $handItem = $player->getInventory()->getItemInHand();
        $namedTag = $handItem->getNamedTag();
        $maxDurability = $event->getItem()->getMaxDurability();

        if ($this->breakwarncfg->get($player->getName()) === "disable") return;

        if ($this->config->get("breakwarn_mode") === "tool"){
            if($namedTag !== null && $namedTag->getTag("BreakWarn") !== null && $handItem->getMeta() >= (0.9 * $maxDurability)){
                $this->sendThingy($player, $event->getItem());
                return;
            }
        }

        if ($this->config->get("breakwarn_mode") === "command" && $handItem->getMeta() >= (0.9 * $maxDurability)) {
            $this->sendThingy($player, $event->getItem());
            return;
        }
    }

    public function onAtack(EntityDamageByEntityEvent $event) : void
    {
        $player = $event->getDamager();
        $handItem = $player->getInventory()->getItemInHand();
        $namedTag = $handItem->getNamedTag();
        $maxDurability = $event->getItem()->getMaxDurability();

        if (!$player instanceof Player) return;

        $playerName = $player->getName();
        if ($this->breakwarncfg->get($player->getName()) === "disable") return;

        if ($this->config->get("breakwarn_mode") === "tool"){
            if($namedTag !== null && $namedTag->getTag("BreakWarn") !== null && $handItem->getMeta() >= (0.9 * $maxDurability)){
                $this->sendThingy($player, $event->getItem());
                return;
            }
        }

        if ($this->config->get("breakwarn_mode") === "command" && $handItem->getMeta() >= (0.9 * $maxDurability)) {
            $this->sendThingy($player, $event->getItem());
            return;
        }
    }

    private function sendItemWarnings(Player $player,Tool $item, $itemType): void {
        $playerName = $player->getName();
        $tierName = match($player->getInventory()->getItemInHand()->getTier()) {
            ToolTier::WOOD() => "wooden",
            ToolTier::STONE() => "stone",
            ToolTier::IRON() => "iron",
            ToolTier::GOLD() => "golden",
            ToolTier::DIAMOND() => "diamond"
        };

        if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat" or ($this->config->get("breakwarn_mode") === "tool" and $item->getNamedTag()->getString("BreakWarn") === "chat")) {
            $player->sendMessage($this->messages->get("{$tierName}{$itemType}Chat"));
        } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup" or ($this->config->get("breakwarn_mode") === "tool" and $item->getNamedTag()->getString("BreakWarn") === "popup")) {
            $player->sendActionBarMessage($this->messages->get("{$tierName}{$itemType}Popup"));
        } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen" or ($this->config->get("breakwarn_mode") === "tool" and $item->getNamedTag()->getString("BreakWarn") === "screen")) {
            $player->sendTitle($this->messages->get("{$tierName}{$itemType}Screen"));
        } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all" or ($this->config->get("breakwarn_mode") === "tool" and $item->getNamedTag()->getString("BreakWarn") === "all")) {
            $player->sendMessage($this->messages->get("{$tierName}{$itemType}Chat"));
            $player->sendActionBarMessage($this->messages->get("{$tierName}{$itemType}Popup"));
            $player->sendTitle($this->messages->get("{$tierName}{$itemType}Screen"));
        }
    }

    private function sendThingy(Player $player, $item): void {
        if ($item instanceof Pickaxe) {
            $this->sendItemWarnings($player, $item, 'Pickaxe');
        } elseif ($item instanceof Shovel) {
            $this->sendItemWarnings($player, $item, 'Shovel');
        } elseif ($item instanceof Hoe) {
            $this->sendItemWarnings($player, $item, 'Hoe');
        } elseif ($item instanceof Axe) {
            $this->sendItemWarnings($player, $item, 'Axe');
        }
    }

}
