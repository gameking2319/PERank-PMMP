<?php


namespace gameking2319\PERanks;


use gameking2319\PERanks\Handlers\PrefixHandler;
use gameking2319\PERanks\Handlers\RankHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat as TF;

class EventListener implements Listener
{

    /**
     * @param PlayerJoinEvent $e
     *
     * This method sets the data for the player if he rejoins
     */
    public function onJoin(PlayerJoinEvent $e){
        $player = $e->getPlayer();
        $playerName = $player->getName();

        PrefixHandler::createUserIfNotExists($playerName);
        RankHandler::createUserIfNotExists($playerName);

        Main::getInstance()->loadPlayerFormat($player);
    }

    /**
     * @param PlayerChatEvent $e
     */
    public function onChat(PlayerChatEvent $e){

        $player = $e->getPlayer();
        $playerName = $player->getName();

        $rank = RankHandler::get($playerName);
        $prefix = PrefixHandler::get($playerName);

        $config = Main::getInstance()->getConfig()->getAll();
        $format = $config["format"];

        $format = str_replace("{prefix}", $prefix, $format);
        $format = str_replace("{rank}", $rank, $format);
        $format = str_replace("{playername}", $playerName, $format);

        $message = $e->getMessage();

        $e->setFormat($format . ": ". $message);
    }

}