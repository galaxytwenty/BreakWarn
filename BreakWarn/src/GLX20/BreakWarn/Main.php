<?php
declare(strict_types=1);
namespace GLX20\BreakWarn;

use GLX20\BreakWarn\commands\breakwarnCommand;
use GLX20\BreakWarn\commands\breakwarnTool;
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


class Main extends PluginBase implements Listener
{
    public $breakwarncfg;
    public $messages;
    public $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->breakwarncfg = new Config($this->getDataFolder()."BreakWarnToogle.yml");
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
        $selectedLanguage = $this->config->get("selectedLanguage");

        if($this->config->get("breakwarn_mode") === "command") {
            $this->getServer()->getCommandMap()->register('breakwarn', new breakwarnCommand($this));
        }
        if($this->config->get("breakwarn_mode") === "tool"){
            $this->getServer()->getCommandMap()->register('breakwarn', new breakwarnTool($this));
        }

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

    public function onBlockBreak(BlockBreakEvent $event) : void
    {
        $player = $event->getPlayer();
        $handItem = $player->getInventory()->getItemInHand();
        $namedTag = $handItem->getNamedTag();
        if(!$event->getItem() instanceof Tool) return ;
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
        $playerString = $event->getDamager()->getNameTag();
        $player = $this->getServer()->getPlayerExact($playerString);
        $handItem = $player->getInventory()->getItemInHand();
        $namedTag = $handItem->getNamedTag();
        if(!$handItem instanceof Tool) return ;
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
