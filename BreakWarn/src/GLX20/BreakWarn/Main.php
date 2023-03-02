<?php
namespace GLX20\BreakWarn;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\Tool;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{

    public $breakwarncfg;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->breakwarncfg = new Config($this->getDataFolder()."BreakWarnToogle.yml");
        $this->getLogger()->info("§4§l[BreakWarn] §aAktiviert.");
    }


    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {

        $player = $sender->getName();
        if($command->getName() === "breakwarn"){
            if(empty($args[0])){
                if(empty($this->breakwarncfg->get($player))){
                    $this->breakwarncfg->set($player, "aktiviert");
                    $this->breakwarncfg->set("$player"."_displayWarn", "popup");
                    $this->breakwarncfg->save();
                    $sender->sendMessage("§cBreak§aWarn §aAktiviert!");
                    $sender->sendMessage("§6Standart Warn Art: §a§lPopup");
                    return false;
                }

                if($this->breakwarncfg->get($player) === "aktiviert"){
                    $this->breakwarncfg->set($player, "deaktiviert");
                    $this->breakwarncfg->save();
                    $sender->sendMessage("§cBreak§aWarn §4Deaktiviert!");
                    return false;
                }

                if($this->breakwarncfg->get($player) === "deaktiviert"){
                    $this->breakwarncfg->set($player, "aktiviert");
                    $this->breakwarncfg->save();
                    $sender->sendMessage("§cBreak§aWarn §aAktiviert!");
                    return false;
                }
            }else{
                if(isset($args[1])){
                    $sender->sendMessage("§6Nutze §a/breakwarn §6<§bWarnArt§6>");
                    $sender->sendMessage("§6Warn Arten: §6(§echat§6)(§epopup§6)(§escreen§6)(§eall§6)");
                    return false;
                }
                if($args[0] === "chat"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "chat");
                    $sender->sendMessage("§6Aktuelle Warn Art: §a§lChat ");
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "popup"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "popup");
                    $sender->sendMessage("§6Aktuelle Warn Art: §a§lPopup ");
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "screen"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "screen");
                    $sender->sendMessage("§6Aktuelle Warn Art: §a§lScreen ");
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "all"){
                    $this->breakwarncfg->set("$player"."_displayWarn", "all");
                    $sender->sendMessage("§6Aktuelle Warn Art: §a§lAll ");
                    $this->breakwarncfg->save();
                    return false;
                }elseif ($args[0] === "help"){
                    $sender->sendMessage("§6Hilfe: §a/breakwarn §6<§bWarnArt§6>");
                    $sender->sendMessage("§6Warn Arten: §6(§echat§6)(§epopup§6)(§escreen§6)(§eall§6)");
                } else{
                    $sender->sendMessage("§cUnbekannte §6Warnungs Art");
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
        $itemObject = $player->getInventory()->getItemInHand();
        $handItem = $player->getInventory()->getItemInHand()->getVanillaName();
        $handMeta = $player->getInventory()->getItemInHand()->getMeta();
        if($this->breakwarncfg->get($playerName) === "deaktiviert"){
            return false;
        }
        if ($player instanceof Player) {
            if ($itemObject instanceof Tool) {
                if ($handItem === "Wooden Pickaxe") {
                    if ($handMeta === 55) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }


                    if ($handMeta === 56) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }


                    if ($handMeta === 57) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }


                    if ($handMeta === 58) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }


                    if ($handMeta === 59) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }


                if ($handItem === "Stone Pickaxe") {
                    if ($handMeta === 127) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 128) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 129) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 130) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 131) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }


                if ($handItem === "Iron Pickaxe") {
                    if ($handMeta === 246) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 247) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 248) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 249) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 250) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }


                if ($handItem === "Golden Pickaxe") {
                    if ($handMeta === 28) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 29) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 30) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 31) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 32) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }


                if ($handItem === "Diamond Pickaxe") {
                    if ($handMeta === 1557) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1558) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1559) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1560) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1561) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }



                if ($handItem === "Wooden Shovel") {
                    if ($handMeta === 55) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 56) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 57) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 58) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 59) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Stone Shovel") {
                    if ($handMeta === 127) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 128) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 129) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 130) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 131) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Iron Shovel") {
                    if ($handMeta === 246) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 247) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 248) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 249) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 250) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Golden Shovel") {
                    if ($handMeta === 28) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 29) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 30) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 31) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 32) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Diamond Shovel") {
                    if ($handMeta === 1557) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1558) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1559) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1560) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1561) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }


                if ($handItem === "Wooden Hoe") {
                    if ($handMeta === 55) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 56) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 57) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 58) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 59) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Stone Hoe") {
                    if ($handMeta === 127) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 128) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 129) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 130) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 131) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Iron Hoe") {
                    if ($handMeta === 246) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 247) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 248) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 249) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 250) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Golden Hoe") {
                    if ($handMeta === 28) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 29) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 30) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 31) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 32) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Diamond Hoe") {
                    if ($handMeta === 1557) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1558) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1559) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1560) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1561) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Wooden Axe") {
                    if ($handMeta === 55) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 56) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 57) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 58) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 59) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Stone Axe") {
                    if ($handMeta === 127) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 128) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 129) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 130) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 131) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Iron Axe") {
                    if ($handMeta === 246) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 247) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 248) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 249) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 250) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Golden Axe") {
                    if ($handMeta === 28) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 29) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 30) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 31) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 32) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Diamond Axe") {
                    if ($handMeta === 1557) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1558) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1559) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1560) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1561) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

            }

                if ($handItem === "Wooden Sword") {
                    if ($handMeta === 52) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 54) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 56) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 58) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Stone Sword") {
                    if ($handMeta === 127) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 128) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 129) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 130) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 131) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Iron Sword") {
                    if ($handMeta === 246) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 247) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 248) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 249) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 250) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Golden Sword") {
                    if ($handMeta === 28) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 29) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 30) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 31) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 32) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }

                if ($handItem === "Diamond Sword") {
                    if ($handMeta === 1557) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1558) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1559) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1560) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }

                    if ($handMeta === 1561) {
                        if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                            $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                            $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                            $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        }else{
                            return false;
                        }
                    }
                }
        }
        return false;
    }

    public function onAtack (EntityDamageByEntityEvent $event)
    {
        $player = $event->getDamager();
        if ($player instanceof Player) {
            $playerName = $player->getName();
            if ($this->breakwarncfg->get($playerName) === "deaktiviert") {
                return false;
            }

            $handItem = $player->getInventory()->getItemInHand()->getVanillaName();
            $handMeta = $player->getInventory()->getItemInHand()->getMeta();
            if ($handItem === "Wooden Pickaxe") {
                if ($handMeta === 55) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }


                if ($handMeta === 56) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }


                if ($handMeta === 57) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }


                if ($handMeta === 58) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }


                if ($handMeta === 59) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Stone Pickaxe") {
                if ($handMeta === 127) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 128) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 129) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 130) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 131) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Spitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Spitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Iron Pickaxe") {
                if ($handMeta === 246) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 247) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 248) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 249) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 250) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSpitzhacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSpitzhacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Golden Pickaxe") {
                if ($handMeta === 28) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 29) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 30) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 31) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 32) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Diamond Pickaxe") {
                if ($handMeta === 1557) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1558) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1559) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1560) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1561) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSpitzhacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSpitzhacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }


            if ($handItem === "Wooden Shovel") {
                if ($handMeta === 55) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 56) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 57) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 58) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 59) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Stone Shovel") {
                if ($handMeta === 127) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 128) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 129) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 130) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 131) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Iron Shovel") {
                if ($handMeta === 246) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 247) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 248) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 249) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 250) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchaufel§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchaufel§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Golden Shovel") {
                if ($handMeta === 28) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 29) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 30) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 31) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 32) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Diamond Shovel") {
                if ($handMeta === 1557) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1558) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1559) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1560) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1561) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchaufel\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchaufel §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }


            if ($handItem === "Wooden Hoe") {
                if ($handMeta === 55) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 56) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 57) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 58) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 59) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Stone Hoe") {
                if ($handMeta === 127) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 128) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 129) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 130) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 131) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Hacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Hacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Iron Hoe") {
                if ($handMeta === 246) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 247) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 248) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 249) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 250) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lAxt§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lAxt§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Golden Hoe") {
                if ($handMeta === 28) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 29) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 30) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 31) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 32) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Diamond Hoe") {
                if ($handMeta === 1557) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1558) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1559) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1560) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1561) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bAxt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bAxt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }


            if ($handItem === "Wooden Axe") {
                if ($handMeta === 55) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 56) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 57) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 58) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 59) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Stone Axe") {
                if ($handMeta === 127) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 128) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 129) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 130) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 131) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Axt\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Axt §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Iron Axe") {
                if ($handMeta === 246) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 247) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 248) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 249) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 250) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lHacke§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lHacke§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Golden Axe") {
                if ($handMeta === 28) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 29) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 30) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 31) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 32) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Diamond Axe") {
                if ($handMeta === 1557) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1558) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1559) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1560) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1561) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bHacke\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bHacke §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Wooden Sword") {
                if ($handMeta === 52) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 54) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 56) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 58) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§6Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §6Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }


            if ($handItem === "Stone Sword") {
                if ($handMeta === 127) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 128) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 129) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 130) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 131) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§7Schwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §7Schwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Iron Sword") {
                if ($handMeta === 246) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 247) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 248) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 249) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 250) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§f§lSchwert§r\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §f§lSchwert§r §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Golden Sword") {
                if ($handMeta === 28) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 29) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 30) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 31) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 32) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§eSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §eSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
            if ($handItem === "Diamond Sword") {
                if ($handMeta === 1557) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1558) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1559) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1560) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }

                if ($handMeta === 1561) {
                    if($this->breakwarncfg->get("$playerName"."_displayWarn") === "chat"){
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "popup"){
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "screen"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                    }elseif($this->breakwarncfg->get("$playerName"."_displayWarn") === "all"){
                        $player->sendTitle("§bSchwert\n§k§fxx§r §4Beschädigt !!! §k§fxx§r");
                        $player->sendActionBarMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                        $player->sendMessage("§k§fxx§r §bSchwert §cBeschädigt !!! §k§fxx§r");
                    }else{
                        return false;
                    }
                }
            }
        }
        return false;
    }

    public function onDisable() : void{
        $this->getLogger()->info("§4§l[BreakWarn] §cDeaktiviert");
    }
}