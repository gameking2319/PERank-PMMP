<?php


namespace gameking2319\PERanks\Handlers;


use gameking2319\PERanks\Main;

class PrefixHandler implements IHandler
{

    public static function createUserIfNotExists(string $playerName){

        // Get main class Properties
        $instance = Main::getInstance();
        $config = $instance->getConfig()->getAll();
        $database = $instance->getDatabase();

        // Query the Database and get the COUNT
        $stmt = $database->prepare("SELECT COUNT(*) as C FROM prefixes WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the query
        $res = $stmt->execute()->fetchArray();

        // Close the statement
        $stmt->close();

        // Look if there are not any users already created
        if($res["C"] === 0){

            // Get The main prefix
            $mainPrefix = $config["starterPrefix"];

            // Create a new user
            $stmt = $database->prepare("INSERT INTO prefixes(player, prefix) VALUES (:player, :prefix);");
            $stmt->bindParam(":player", $playerName);
            $stmt->bindParam(":prefix", $mainPrefix);

            // Execute the Create user Query
            $stmt->execute();

            return;
        }

    }

    public static function set(string $playerName, string $name)
    {
        // Get main class Properties
        $instance = Main::getInstance();
        $database = $instance->getDatabase();

        // Query the Database and get the COUNT
        $stmt = $database->prepare("SELECT COUNT(*) as C FROM prefixes WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the query
        $res = $stmt->execute()->fetchArray();

        // Close the statement
        $stmt->close();

        // Look if there are not any users already created
        if ($res["C"] === 0) return;

        // Query the database to get update the prefix
        $stmt = $database->prepare("UPDATE prefixes SET prefix = :prefix WHERE player = :player;");
        $stmt->bindParam(":prefix", $name);
        $stmt->bindParam(":player", $playerName);

        // Execute the Query
        $stmt->execute();

        // Close Statement
        $stmt->close();
    }

    public static function get(string $playerName): string
    {
        // Get main class Properties
        $instance = Main::getInstance();
        $database = $instance->getDatabase();

        // Query the Database and get the COUNT
        $stmt = $database->prepare("SELECT COUNT(*) as C FROM prefixes WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the query
        $res = $stmt->execute()->fetchArray();

        // Close the statement
        $stmt->close();

        // Look if there are not any users already created
        if ($res["C"] === 0) return "";

        // Query the Database and get the players with a specific name
        $stmt = $database->prepare("SELECT * FROM prefixes WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the prefix Query
        $res = $stmt->execute()->fetchArray();

        // Return the prefix
        return $res["prefix"];
    }

    public static function reset(string $playerName)
    {
        $instance = Main::getInstance();
        $config = $instance->getConfig()->getAll();

        self::set($playerName, $config["starterPrefix"]);
    }
}