<?php

declare(strict_types=1);

namespace gameking2319\PERanks;

use gameking2319\PERanks\Command\PERankCommand;
use gameking2319\PERanks\Handlers\PrefixHandler;
use gameking2319\PERanks\Handlers\RankHandler;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase{

    /** @var Main */
    private static $instance;

    /** @var Config */
    private static $config;

    /** @var \SQLite3 */
    private static $database;

    public function onEnable()
    {
        // Give the instance a $this value
        self::$instance = $this;

        // Make the config
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "starterRank"=> TF::AQUA . "Player",
            "starterPrefix"=> "",

            "format"=>"§f[{prefix}§f] §f[{rank}§f] §4=> §8{playername}"
        ]);

        // Making the database
        self::$database = new \SQLite3($this->getDataFolder() . "database.db");

        // Create the rank and prefix database
        self::$database->query("CREATE TABLE IF NOT EXISTS ranks(
            player TEXT NOT NULL,
            rank TEXT NOT NULL
        );");

        self::$database->query("CREATE TABLE IF NOT EXISTS prefixes(
            player TEXT NOT NULL,
            prefix TEXT NOT NULL
        );");

        // Register the listener
        $this->getServer()->getPluginManager()->registerEvents(new EventListener, $this);

        // Create command instance
        $command = new PERankCommand("perank", $this);

        // Set command Description
        $command->setDescription("Simple Rank / Prefix plugin");

        // Register the command
        $this->getServer()->getCommandMap()->register("perank", $command);

    }

    /**
     * @return Main
     */
    public static function getInstance():Main
    {
        return self::$instance;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return self::$config;
    }

    /**
     * @return \SQLite3
     */
    public function getDatabase(): \SQLite3
    {
        return self::$database;
    }

    /**
     * @return array
     *
     * get all the playerNames of the online players
     */
    public function getPlayerNames():array {
        $playerNames = [];
        $players = $this->getServer()->getOnlinePlayers();

        foreach ($players as $player){
            $playerNames[] = $player->getName();
        }

        return $playerNames;
    }

    /**
     * @param Player $player
     *
     * This method loads the prefix of the player
     */
    public function loadPlayerFormat(Player $player){
        $playerName = $player->getName();

        $rank = RankHandler::get($playerName);
        $prefix = PrefixHandler::get($playerName);

        $config = Main::getInstance()->getConfig()->getAll();
        $format = $config["format"];

        $format = str_replace("{prefix}", $prefix, $format);
        $format = str_replace("{rank}", $rank, $format);
        $format = str_replace("{playername}", $playerName, $format);

        $player->setNameTag($format);
    }
}
