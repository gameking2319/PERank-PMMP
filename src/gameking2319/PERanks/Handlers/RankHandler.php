<?php


namespace gameking2319\PERanks\Handlers;


use gameking2319\PERanks\Main;

class RankHandler implements IHandler
{

    public static function createUserIfNotExists(string $playerName){

        // Get main class Properties
        $instance = Main::getInstance();
        $config = $instance->getConfig()->getAll();
        $database = $instance->getDatabase();

        // Query the Database and get the COUNT
        $stmt = $database->prepare("SELECT COUNT(*) as C FROM ranks WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the query
        $res = $stmt->execute()->fetchArray();

        // Close the statement
        $stmt->close();

        // Look if there are not any users already created
        if($res["C"] === 0){

            // Get The main Rank
            $mainRank = $config["starterRank"];

            // Create a new user
            $stmt = $database->prepare("INSERT INTO ranks(player, rank) VALUES (:player, :rank);");
            $stmt->bindParam(":player", $playerName);
            $stmt->bindParam(":rank", $mainRank);

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
        $stmt = $database->prepare("SELECT COUNT(*) as C FROM ranks WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the query
        $res = $stmt->execute()->fetchArray();

        // Close the statement
        $stmt->close();

        // Look if there is a user whit that name
        if ($res["C"] === 0) return;

        // Query the database to get update the rank
        $stmt = $database->prepare("UPDATE ranks SET rank = :rank WHERE player = :player;");
        $stmt->bindParam(":rank", $name);
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
        $stmt = $database->prepare("SELECT COUNT(*) as C FROM ranks WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the query
        $res = $stmt->execute()->fetchArray();

        // Close the statement
        $stmt->close();

        // Look if there are not any users already created
        if ($res["C"] === 0) return "";

        // Query the Database and get the players with a specific name
        $stmt = $database->prepare("SELECT * FROM ranks WHERE player = :player;");
        $stmt->bindParam(":player", $playerName);

        // Get the results of the ranks Query
        $res = $stmt->execute()->fetchArray();

        // return the tank
        return $res["rank"];
    }

    public static function reset(string $playerName)
    {
        $instance = Main::getInstance();
        $config = $instance->getConfig()->getAll();

        self::set($playerName, $config["starterRank"]);
    }
}