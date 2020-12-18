<?php


namespace gameking2319\PERanks\Command;


use gameking2319\PERanks\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use gameking2319\PERanks\Utils\GUIHandler;

class PERankCommand extends PluginCommand
{
    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {

        $main = Main::getInstance();

        if($sender instanceof Player){
            if($sender->hasPermission("peranks")){
                GUIHandler::openGUI($sender);
            }
            return true;
        }

        $main->getLogger()->warning("This command cant be used.");

        return true;
    }

}