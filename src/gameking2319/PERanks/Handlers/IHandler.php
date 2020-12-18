<?php


namespace gameking2319\PERanks\Handlers;


interface IHandler
{

    /**
     * @param string $playerName
     * @return void
     *
     * This method create the prefix / rank for the user if he does not exists
     */
    public static function createUserIfNotExists(string $playerName);

    /**
     * @param string $playerName
     * @param string $name
     * @return mixed
     *
     * This method will set the prefix / rank of a player
     */
    public static function set(string $playerName, string $name);

    /**
     * @param string $playerName
     * @param string $name
     * @return string
     *
     * This method will get the prefix / rank of the player
     */
    public static function get(string $playerName): string;

    /**
     * @param string $playerName
     * @return mixed
     *
     * This method will reset the prefix / rank of the player
     */
    public static function reset(string $playerName);
}