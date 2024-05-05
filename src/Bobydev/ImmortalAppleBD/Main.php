<?php
namespace Bobydev\ImmortalAppleBD;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;
use Vecnavium\FormsUI\SimpleForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\entity\effect\VanillaEffects;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "ImmortalAppleBD made by Bobydev enabled 👍!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "bdapple") {
            if ($sender instanceof Player) {
                if ($sender->hasPermission("use.bdapple")) {
                    $this->giveImmortalApple($sender);
                } else {
                    $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
                }
            } else {
                $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
            }
            return true;
        }
        return false;
    }

    private function giveImmortalApple(Player $player): void {
        $apple = VanillaItems::GOLDEN_APPLE()->setCustomName(TextFormat::GOLD . "Immortal Apple");
        $lore = [
            TextFormat::LIGHT_PURPLE . "This enchanted apple grants you",
            TextFormat::LIGHT_PURPLE . "25 extra hearts of health!"
        ];
        $apple->getNamedTag()->setString("description", implode("\n", $lore));
        $player->getInventory()->addItem($apple);
        $player->sendMessage(TextFormat::GREEN . "You have received an Immortal Apple!");
    }

    public function onConsume(PlayerItemConsumeEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($item->getCustomName() === TextFormat::GOLD . "Immortal Apple") {
            $effect = VanillaEffects::HEALTH_BOOST();
            $player->getEffects()->add(new EffectInstance($effect, 6000, 4, false));
            $player->sendMessage(TextFormat::GREEN . "You feel the power of the Immortal Apple!");
        }
    }
}
