<?php
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

class Main extends PluginBase implements Listener
{
    private config $config;
    public $breakwarncfg;
    private $messages;

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

    public function WarnOnBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $playerName = $event->getPlayer()->getName();
        $handItem = $player->getInventory()->getItemInHand();
        $handMeta = $player->getInventory()->getItemInHand()->getMeta();
        if ($this->breakwarncfg->get($playerName) === "disable") {
            return false;
        }
        if ($player instanceof Player) {
            if ($event->getItem() instanceof Pickaxe && $event->getItem()->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 53) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenPickaxePopup"));
                        $player->sendTitle($this->messages->get("woodenPickaxeScreen"));
                    } else {
                        return false;
                    }
                }

            }
            if ($event->getItem() instanceof Pickaxe && $event->getItem()->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 125) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stonePickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stonePickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stonePickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stonePickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("stonePickaxePopup"));
                        $player->sendTitle($this->messages->get("stonePickaxeScreen"));

                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Pickaxe && $event->getItem()->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 244) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("ironPickaxePopup"));
                        $player->sendTitle($this->messages->get("ironPickaxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Pickaxe && $event->getItem()->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 26) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenPickaxePopup"));
                        $player->sendTitle($this->messages->get("goldenPickaxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Pickaxe && $event->getItem()->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondPickaxePopup"));
                        $player->sendTitle($this->messages->get("diamondPickaxeScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($event->getItem() instanceof Shovel && $event->getItem()->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenShovelPopup"));
                        $player->sendTitle($this->messages->get("woodenShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Shovel && $event->getItem()->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneShovelPopup"));
                        $player->sendTitle($this->messages->get("stoneShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Shovel && $event->getItem()->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("ironShovelPopup"));
                        $player->sendTitle($this->messages->get("ironShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Shovel && $event->getItem()->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenShovelPopup"));
                        $player->sendTitle($this->messages->get("goldenShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Shovel && $event->getItem()->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondShovelPopup"));
                        $player->sendTitle($this->messages->get("diamondShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($event->getItem() instanceof Hoe && $event->getItem()->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenHoePopup"));
                        $player->sendTitle($this->messages->get("woodenHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Hoe && $event->getItem()->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneHoePopup"));
                        $player->sendTitle($this->messages->get("stoneHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Hoe && $event->getItem()->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("ironHoePopup"));
                        $player->sendTitle($this->messages->get("ironHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Hoe && $event->getItem()->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenHoePopup"));
                        $player->sendTitle($this->messages->get("goldenHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Hoe && $event->getItem()->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondHoePopup"));
                        $player->sendTitle($this->messages->get("diamondHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($event->getItem() instanceof Axe && $event->getItem()->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenAxePopup"));
                        $player->sendTitle($this->messages->get("woodenAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Axe && $event->getItem()->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneAxePopup"));
                        $player->sendTitle($this->messages->get("stoneAxeScreen"));
                    } else {
                        return false;
                    }
                }

            }
            if ($event->getItem() instanceof Axe && $event->getItem()->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("ironAxePopup"));
                        $player->sendTitle($this->messages->get("ironAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Axe && $event->getItem()->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenAxePopup"));
                        $player->sendTitle($this->messages->get("goldenAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Axe && $event->getItem()->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondAxePopup"));
                        $player->sendTitle($this->messages->get("diamondAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($event->getItem() instanceof Sword && $event->getItem()->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 50) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenSwordPopup"));
                        $player->sendTitle($this->messages->get("woodenSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Sword && $event->getItem()->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneSwordPopup"));
                        $player->sendTitle($this->messages->get("stoneSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Sword && $event->getItem()->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("ironSwordPopup"));
                        $player->sendTitle($this->messages->get("ironSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Sword && $event->getItem()->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenSwordPopup"));
                        $player->sendTitle($this->messages->get("goldenSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($event->getItem() instanceof Sword && $event->getItem()->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondSwordPopup"));
                        $player->sendTitle($this->messages->get("diamondSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    public function onAtack (EntityDamageByEntityEvent $event)
    {
        $player = $event->getDamager();
        if ($player instanceof Player) {
            $playerName = $player->getName();
            if ($this->breakwarncfg->get($playerName) === "disable") {
                return false;
            }
            $handMeta = $player->getInventory()->getItemInHand()->getMeta();
            $handItem = $player->getInventory()->getItemInHand();
            if ($handItem instanceof Pickaxe && $handItem->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenPickaxePopup"));
                        $player->sendTitle($this->messages->get("woodenPickaxeScreen"));
                    } else {
                        return false;
                    }
                }

            }
            if ($handItem instanceof Pickaxe && $handItem->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stonePickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stonePickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stonePickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stonePickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("stonePickaxePopup"));
                        $player->sendTitle($this->messages->get("stonePickaxeScreen"));

                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Pickaxe && $handItem->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("ironPickaxePopup"));
                        $player->sendTitle($this->messages->get("ironPickaxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Pickaxe && $handItem->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenPickaxePopup"));
                        $player->sendTitle($this->messages->get("goldenPickaxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Pickaxe && $handItem->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondPickaxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondPickaxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondPickaxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondPickaxeChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondPickaxePopup"));
                        $player->sendTitle($this->messages->get("diamondPickaxeScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($handItem instanceof Shovel && $handItem->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenShovelPopup"));
                        $player->sendTitle($this->messages->get("woodenShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Shovel && $handItem->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneShovelPopup"));
                        $player->sendTitle($this->messages->get("stoneShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Shovel && $handItem->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("ironShovelPopup"));
                        $player->sendTitle($this->messages->get("ironShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Shovel && $handItem->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenShovelPopup"));
                        $player->sendTitle($this->messages->get("goldenShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Shovel && $handItem->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondShovelChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondShovelPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondShovelScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondShovelChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondShovelPopup"));
                        $player->sendTitle($this->messages->get("diamondShovelScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($handItem instanceof Hoe && $handItem->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenHoePopup"));
                        $player->sendTitle($this->messages->get("woodenHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Hoe && $handItem->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneHoePopup"));
                        $player->sendTitle($this->messages->get("stoneHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Hoe && $handItem->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("ironHoePopup"));
                        $player->sendTitle($this->messages->get("ironHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Hoe && $handItem->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenHoePopup"));
                        $player->sendTitle($this->messages->get("goldenHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Hoe && $handItem->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondHoeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondHoePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondHoeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondHoeChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondHoePopup"));
                        $player->sendTitle($this->messages->get("diamondHoeScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($handItem instanceof Axe && $handItem->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 54) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenAxePopup"));
                        $player->sendTitle($this->messages->get("woodenAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Axe && $handItem->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneAxePopup"));
                        $player->sendTitle($this->messages->get("stoneAxeScreen"));
                    } else {
                        return false;
                    }
                }

            }
            if ($handItem instanceof Axe && $handItem->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("ironAxePopup"));
                        $player->sendTitle($this->messages->get("ironAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Axe && $handItem->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenAxePopup"));
                        $player->sendTitle($this->messages->get("goldenAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Axe && $handItem->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondAxeChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondAxePopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondAxeScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondAxeChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondAxePopup"));
                        $player->sendTitle($this->messages->get("diamondAxeScreen"));
                    } else {
                        return false;
                    }
                }
            }

            if ($handItem instanceof Sword && $handItem->getTier() === ToolTier::WOOD()) {
                if ($handMeta >= 50) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("woodenSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("woodenSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("woodenSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("woodenSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("woodenSwordPopup"));
                        $player->sendTitle($this->messages->get("woodenSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Sword && $handItem->getTier() === ToolTier::STONE()) {
                if ($handMeta >= 126) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("stoneSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("stoneSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("stoneSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("stoneSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("stoneSwordPopup"));
                        $player->sendTitle($this->messages->get("stoneSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Sword && $handItem->getTier() === ToolTier::IRON()) {
                if ($handMeta >= 245) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("ironSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("ironSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("ironSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("ironSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("ironSwordPopup"));
                        $player->sendTitle($this->messages->get("ironSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
            if ($handItem instanceof Sword && $handItem->getTier() === ToolTier::GOLD()) {
                if ($handMeta >= 27) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("goldenSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("goldenSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("goldenSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("goldenSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("goldenSwordPopup"));
                        $player->sendTitle($this->messages->get("goldenSwordScreen"));
                    } else {
                        return false;
                    }
                }

            }
            if ($handItem instanceof Sword && $handItem->getTier() === ToolTier::DIAMOND()) {
                if ($handMeta >= 1556) {
                    if ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "chat") {
                        $player->sendMessage($this->messages->get("diamondSwordChat"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "popup") {
                        $player->sendActionBarMessage($this->messages->get("diamondSwordPopup"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "screen") {
                        $player->sendTitle($this->messages->get("diamondSwordScreen"));

                    } elseif ($this->breakwarncfg->get("$playerName" . "_displayWarn") === "all") {
                        $player->sendMessage($this->messages->get("diamondSwordChat"));
                        $player->sendActionBarMessage($this->messages->get("diamondSwordPopup"));
                        $player->sendTitle($this->messages->get("diamondSwordScreen"));
                    } else {
                        return false;
                    }
                }
            }
        }
        return false;
    }
}
