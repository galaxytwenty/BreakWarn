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
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

final class Main extends PluginBase implements Listener
{
    private Config $breakwarncfg;
    private Config $messages;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->breakwarncfg = new Config($this->getDataFolder()."BreakWarnToogle.yml");
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
        if($command->getName() === "breakwarn"){
            if(!$sender->hasPermission("breakwarn.allow")){
                return false;
            }
            if(empty($args[0])){
                if(empty($this->breakwarncfg->get($player))){
                    $this->breakwarncfg->set($player, "enabled");
                    $this->breakwarncfg->set("$player"."_displayWarn", "popup");
                    $this->breakwarncfg->save();
                    $sender->sendMessage($this->messages->get("breakwarnEnable"));
                    $sender->sendMessage($this->messages->get("standartWarn"));
                    return false;
                }
                if($this->breakwarncfg->get($player) === "enabled"){
                    $this->breakwarncfg->set($player, "disable");
                    $this->breakwarncfg->save();
                    $sender->sendMessage($this->messages->get("breakwarnDisable"));
                    return false;
                }

                if($this->breakwarncfg->get($player) === "disable"){
                    $this->breakwarncfg->set($player, "enabled");
                    $this->breakwarncfg->save();
                    $sender->sendMessage($this->messages->get("breakwarnEnable"));
                    return false;
                }
            }else{
                if(isset($args[1])){
                    $sender->sendMessage($this->messages->get("breakwarnUse"));
                    $sender->sendMessage($this->messages->get("breakwarnTypes"));
                    return false;
                }
                if($args[0] === "chat"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "chat");
                    $sender->sendMessage($this->messages->get("typeChat"));
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "popup"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "popup");
                    $sender->sendMessage($this->messages->get("typePopup"));
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "screen"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "screen");
                    $sender->sendMessage($this->messages->get("typeScreen"));
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "all"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "all");
                    $sender->sendMessage($this->messages->get("typeAll"));
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "help"){
                    $sender->sendMessage($this->messages->get("breakwarnUse"));
                    $sender->sendMessage($this->messages->get("breakwarnTypes"));
                } else{
                    $sender->sendMessage($this->messages->get("typeUnknown"));
                    return false;
                }
            }

        }
        return false;
    }

    public function onBlockBreak(BlockBreakEvent $event) : void
    {
        $player = $event->getPlayer();
        $playerName = $event->getPlayer()->getName();
        $handItem = $player->getInventory()->getItemInHand();
        $handMeta = $player->getInventory()->getItemInHand()->getMeta();
        if ($this->breakwarncfg->get($playerName) === "disable") {
            return;
        }
        if($handMeta < (0.9 * $event->getItem()->getMaxDurability())) return;

        $this->sendThingy($event->getItem());
    }

    public function onAtack(EntityDamageByEntityEvent $event) : void
    {
        $player = $event->getDamager();
        if (!$player instanceof Player) return;

        $playerName = $player->getName();
        if ($this->breakwarncfg->get($playerName) === "disable") {
            return;
        }

        $handMeta = $player->getInventory()->getItemInHand()->getMeta();
        if($player->getInventory()->getItemInHand()->getMeta() < (0.9 * $event->getItem()->getMaxDurability())) return;

        $handItem = $player->getInventory()->getItemInHand();
        $this->sendThingy($handItem);
    }

    private function sendThingy(Player $player, TieredTool $item) : void {
        $playerName = $player->getName();
        $tierName = match($event->getItem()->getTier()) {
            ToolTier::WOOD() => "wooden",
            ToolTier::STONE() => "stone",
            ToolTier::IRON() => "iron",
            ToolTier::GOLD() => "golden",
            ToolTier::DIAMOND() => "diamond"
        };
        if ($item instanceof Pickaxe) {
                if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                    $player->sendMessage($this->messages->get("{$tierName}PickaxeChat"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                    $player->sendActionBarMessage($this->messages->get("{$tierName}PickaxePopup"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                    $player->sendTitle($this->messages->get("{$tierName}PickaxeScreen"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                    $player->sendMessage($this->messages->get("{$tierName}PickaxeChat"));
                    $player->sendActionBarMessage($this->messages->get("{$tierName}PickaxePopup"));
                    $player->sendTitle($this->messages->get("{$tierName}PickaxeScreen"));
                }
        }elseif($item instanceof Shovel) {
                if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                    $player->sendMessage($this->messages->get("{$tierName}ShovelChat"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                    $player->sendActionBarMessage($this->messages->get("{$tierName}ShovelPopup"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                    $player->sendTitle($this->messages->get("{$tierName}ShovelScreen"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                    $player->sendMessage($this->messages->get("{$tierName}ShovelChat"));
                    $player->sendActionBarMessage($this->messages->get("{$tierName}ShovelPopup"));
                    $player->sendTitle($this->messages->get("{$tierName}ShovelScreen"));
                }
        }elseif($item instanceof Hoe) {
                if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                    $player->sendMessage($this->messages->get("{$tierName}HoeChat"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                    $player->sendActionBarMessage($this->messages->get("{$tierName}HoePopup"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                    $player->sendTitle($this->messages->get("{$tierName}HoeScreen"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                    $player->sendMessage($this->messages->get("{$tierName}HoeChat"));
                    $player->sendActionBarMessage($this->messages->get("{$tierName}HoePopup"));
                    $player->sendTitle($this->messages->get("{$tierName}HoeScreen"));
                }
        }elseif($item instanceof Axe) {
                if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                    $player->sendMessage($this->messages->get("{$tierName}AxeChat"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                    $player->sendActionBarMessage($this->messages->get("{$tierName}AxePopup"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                    $player->sendTitle($this->messages->get("{$tierName}AxeScreen"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                    $player->sendMessage($this->messages->get("{$tierName}AxeChat"));
                    $player->sendActionBarMessage($this->messages->get("{$tierName}AxePopup"));
                    $player->sendTitle($this->messages->get("{$tierName}AxeScreen"));
                }
        }elseif($item instanceof Sword) {
                if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                    $player->sendMessage($this->messages->get("{$tierName}SwordChat"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                    $player->sendActionBarMessage($this->messages->get("{$tierName}SwordPopup"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                    $player->sendTitle($this->messages->get("{$tierName}SwordScreen"));
                } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                    $player->sendMessage($this->messages->get("{$tierName}SwordChat"));
                    $player->sendActionBarMessage($this->messages->get("{$tierName}SwordPopup"));
                    $player->sendTitle($this->messages->get("{$tierName}SwordScreen"));
                }
        }
    }
}
