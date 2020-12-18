<?php

namespace gameking2319\PERanks\Utils;


use gameking2319\PERanks\Handlers\PrefixHandler;
use gameking2319\PERanks\Handlers\RankHandler;
use gameking2319\PERanks\Main;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class GUIHandler
{

    /**
     * @param Player $player
     *
     * This method opens the option GUI
     */
    public static function openGUI(Player $player){
        $form = new SimpleForm(function(Player $player, $data){

            if($data === null)return;

            if($data === 0)self::setRank($player);
            if($data === 1)self::setPrefix($player);
            if($data === 2)self::resetRank($player);
            if($data === 3)self::resetPrefix($player);

        });

        $form->setTitle(TF::AQUA . "PERanks");

        $form->addButton("Set Rank");
        $form->addButton("Set Prefix");
        $form->addButton("Reset Rank");
        $form->addButton("Reset Prefix");

        $player->sendForm($form);
    }

    public static function setPrefix(Player $player){
        $playerNames = Main::getInstance()->getPlayerNames();

        $form = new CustomForm(function(Player $player, $data) use ($playerNames){
            if(!isset($data[0]))return;
            if(!isset($data[1]))return;

            PrefixHandler::set($playerNames[$data[0]], $data[1]);

            Main::getInstance()->loadPlayerFormat($player);
        });

        $form->setTitle(TF::AQUA . "PERanks");

        $form->addDropdown("Players", $playerNames);
        $form->addInput("Prefix Name", "Text....");

        $player->sendForm($form);
    }

    public static function setRank(Player $player){
        $playerNames = Main::getInstance()->getPlayerNames();

        $form = new CustomForm(function(Player $player, $data) use ($playerNames){
            if(!isset($data[0]))return;
            if(!isset($data[1]))return;

            RankHandler::set($playerNames[$data[0]], $data[1]);

            Main::getInstance()->loadPlayerFormat($player);
        });

        $form->setTitle(TF::AQUA . "PERanks");

        $form->addDropdown("Players", $playerNames);
        $form->addInput("Rank Name", "Text....");

        $player->sendForm($form);
    }

    public static function resetPrefix(Player $player){
        $playerNames = Main::getInstance()->getPlayerNames();

        $form = new CustomForm(function(Player $player, $data) use ($playerNames){
            if(!isset($data[0]))return;

            PrefixHandler::reset($playerNames[$data[0]]);

            Main::getInstance()->loadPlayerFormat($player);
        });

        $form->setTitle(TF::AQUA . "PERanks");

        $form->addDropdown("Players", $playerNames);

        $player->sendForm($form);
    }

    public static function resetRank(Player $player){
        $playerNames = Main::getInstance()->getPlayerNames();

        $form = new CustomForm(function(Player $player, $data) use ($playerNames){
            if(!isset($data[0]))return;

            RankHandler::reset($playerNames[$data[0]]);

            Main::getInstance()->loadPlayerFormat($player);
        });

        $form->setTitle(TF::AQUA . "PERanks");

        $form->addDropdown("Players", $playerNames);

        $player->sendForm($form);

    }


}