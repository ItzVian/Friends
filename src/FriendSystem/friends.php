<?php

#This plugin was written by Scamnex
#Re-encoding or modifying is prohibited
#»2019 | Scamnex | FriendSystem

namespace FriendSystem;


use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\command\{CommandSender, Command
};
use pocketmine\event\player\{
    PlayerJoinEvent, 
    PlayerQuitEvent,
    PlayerChatEvent
};

class friends extends PluginBase implements Listener{
    
    
   public $prefix = "§l§6FRIENDS§r§b »§r ";
   
   public function onEnable(): void{
       @mkdir($this->getDataFolder());
       $this->getServer()->getPluginManager()->registerEvents($this, $this);
       $this->getLogger()->info($this->prefix."§aactivated by ItzVian");
} 

     public function onJoin(PlayerJoinEvent $event){
         $player = $event->getPlayer();
         $name = $player->getName();
         //FILES
         if(!file_exists($this->getDataFolder().$name.".yml")){
             $playerfile = new Config($this->getDataFolder().$name.".yml", Config::YAML);
             $playerfile->set("Friend", array());
             $playerfile->set("Invitations", array());
$playerfile->set("blocked", false);
             $playerfile->save();
             
         }else{
             $playerfile = new Config($this->getDataFolder().$name.".yml", Config::YAML);
             if(!empty($playerfile->get("Invitations"))){
                 foreach($playerfile->get("Invitations") as $e){
                     $player->sendMessage($this->prefix."§a".$e."§r§e is now your friend!");
           }
}
       if(!empty($playerfile->get("Friend"))){
       foreach($playerfile->get("Friend") as $f){
       $v = $this->getServer()->getPlayerExact($f);
if(!$v == null){
       $v->sendMessage($this->prefix."§a".$player->getName()." §eis online now");
                  }
              }
          }
      }
  }
     
     
     public function onQuit(PlayerQuitEvent $event){
         $player = $event->getPlayer();
         $name = $player->getName();
         //FILES ON QUIT
         $playerfile = new Config($this->getDataFolder().$name.".yml", Config::YAML);
         if(!empty($playerfile->get("Friend"))){
             foreach($playerfile->get("Friend") as $f){
                 $v = $this->getServer()->getPlayerExact($f);
                 if(!$v == null){
$v->sendMessage($this->prefix."§a".$player->getName()." §6is online now");
           }
       }
    }
}
     
     public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
         if($cmd->getName() == "friends"){
             if($sender instanceof Player){
                 $playerfile = new Config($this->getDataFolder().$sender->getName().".yml", Config::YAML);
if(empty($args[0])){
    
    $sender->sendMessage("§l§eFRIEND SYSTEM§r");
    
    $sender->sendMessage("§6/friends §aaccept » §b Accept a friend request");
    
    $sender->sendMessage("§6/friends §ainvite » §b Send a friend request");
    
    $sender->sendMessage("§6/friends §alist » §blist your friends");
    
    $sender->sendMessage("§6/friends §adeny » §bRefuse a friend request");
    
    $sender->sendMessage("§6/friends §aremove » §bremove a friend");
    
    $sender->sendMessage("§6/friends §ablock » §fDisable your friend request");
    
        }else{
            
            if($args[0] == "invite"){
                if(empty($args[1])){
                    $sender->sendMessage($this->prefix."§eUsage: §2/friends invite <player>");

                }else{
    
    if(file_exists($this->getDataFolder().$args[1].".yml")){
        
        $vplayerfile = new Config($this->getDataFolder().$args[1].".yml", Config::YAML);
        if($vplayerfile->get("blocked") == false){
            $einladungen = $vplayerfile->get("Invitations");
            $einladungen[] = $sender->getName();
            $vplayerfile->set("Invitations", $einladungen);
            $vplayerfile->save();
            $sender->sendMessage($this->prefix."§aYour friend request has been sent to  ".$args[1]);
            $v = $this->getServer()->getPlayerExact($args[1]);
            if(!$v == null){
                $v->sendMessage("§a".$sender->getName()." §r§esent you a friend request accept her with /friends accept ".$sender->getName()."] or reject her with /friends deny ".$sender->getName()."§a!");
               }
            
        }else{
            
            $sender->sendMessage($this->prefix."§aThis player did not accept your friend request");
            
        }
        
    }else{
        $sender->sendMessage($this->prefix."§aThis player is not online");
         }
     }
}
        if($args[0] == "accept"){
            if(empty($args[1])){
                $sender->sendMessage($this->prefix."§eUsage: §2/friends accept <player>");
                
            }else{
                if(file_exists($this->getDataFolder().$args[1].".yml")){
                    $vplayerfile = new Config($this->getDataFolder().$args[1].".yml", Config::YAML);
                    if(in_array($args[1], $playerfile->get("Invitations"))){
                        $old = $playerfile->get("Invitations");
unset($old[array_search($args[1], $old)]);
    $playerfile->set("Invitations", $old);
    $newfriend = $playerfile->get("Friend");
     $newfriend[] = $args[1];
      $playerfile->set("Friend", $newfriend);
      $playerfile->save();

      $vplayerfile = new Config($this->getDataFolder().$args[1].".yml", Config::YAML);
      $newfriend = $vplayerfile->get("Friend");
      $newfriend[] = $sender->getName();
      $vplayerfile->set("Friend", $newfriend);
      $vplayerfile->save();
      
      if(!$this->getServer()->getPlayerExact($args[1]) == null){
          $this->getServer()->getPlayerExact($args[1])->sendMessage($this->prefix."§a".$sender->getName()." §eaccepted your friend request");
      }
      $sender->sendMessage($this->prefix."§a".$args[1]." is your friend now");
                        
                    }else{
                        $sender->sendMessage($this->prefix."§aThis player did not send you a friend request");
                    }
                    
                }else{
                    $sender->sendMessage($this->prefix."§aThis player does not exist");
       }
    }
}
      if($args[0] == "deny"){
          if(empty($args[1])){
              $sender->sendMessage($this->prefix."§eUsage: §2/friends deny <player>");
              
          }else{
              if(file_exists($this->getDataFolder().$args[1].".yml")){
                  $vplayerfile = new Config($this->getDataFolder().$args[1].".yml", Config::YAML);
                  if(in_array($args[1],
                  $playerfile->get("Invitations"))){
                      $old =$playerfile->get("Invitations");
                      unset($old[array_search($args[1], $old)]);
                      $playerfile->set("Invitations", $old);
                      $playerfile->save();

$sender->sendMessage($this->prefix."§aThe request of ".$args[1]."§e was rejected");
                      
                  }else{
$sender->sendMessage($this->prefix."§cThis player did not send you a friend request");
                  }
                  
              }else{
$sender->sendMessage($this->prefix."§cThis player does not exist");
        }
    }
}
       if($args[0] == "remove"){
           if(empty($args[1])){
               $sender->sendMessage($this->prefix." §e/friends remove <player>");
               
           }else{
               if(file_exists($this->getDataFolder().$args[1].".yml")){
                   $vplayerfile = new Config($this->getDataFolder().$args[1].".yml", Config::YAML);
                   if(in_array($args[1], $playerfile->get("Friend"))){
                       $old = $playerfile->get("Friend");
                       unset($old[array_search($args[1], $old)]);
                       $playerfile->set("Friend", $old);
                       $playerfile->save();
                       $vplayerfile = new Config($this->getDataFolder().$args[1].".yml", Config::YAML);
                       $old = $vplayerfile->get("Friend");
                       unset($old[array_search($sender->getName(), $old)]);
                       $vplayerfile->set("Friend", $old);
                       $vplayerfile->save();
                       $sender->sendMessage($this->prefix."§a".$args[1]." §bis no longer your friend");
                       
                   }else{
$sender->sendMessage($this->prefix."§aThis player is not your friend");
                   }
                   
               }else{
$sender->sendMessage($this->prefix."§aThis player does not exist");
         }
     }
}
      if($args[0] == "list"){
          if(empty($playerfile->get("Friend"))){
              $sender->sendMessage($this->prefix."§aYou have no friends");
              
          }else{
              $sender->sendMessage("§l§RYOUR CURRENT FRIENDS§7:§r§b");
              foreach($playerfile->get("Friend") as $f){
                  if($this->getServer()->getPlayerExact($f) == null){
                      $sender->sendMessage("§e".$f." » §7(§coffline§7)");
                      
                  }else{
    
    $sender->sendMessage("§e".$f." » §7(§aonline§7)");
            }
        }
    }
}
      if($args[0] == "block"){
       if($playerfile->get("blocked") === false){
       $playerfile->set("blocked", true);
       $playerfile->save();
       $sender->sendMessage($this->prefix."§aYou will no longer receive a friend request");
           
       }else{
$sender->sendMessage($this->prefix."§aYou will now get friend request again");
      $playerfile->set("blocked", false);
      $playerfile->save();
            }
      }
}
                 
             }else{
$this->getLogger()->info($this->prefix."§aThe console has no friends :)");
        }
    }
return true;
}
        public function onChat(PlayerChatEvent $event){
            $player = $event->getPlayer();
            $msg = $event->getMessage();
            $playerfile = new Config($this->getDataFolder().$player->getName().".yml", Config::YAML);
            $words = explode(" ", $msg);
            if(in_array(str_replace("@", "", $player), $playerfile->get("Friend"))){
                $f = $this->getServer()->getPlayerExact(str_replace("@", "", $player));
                if(!$f == null){
$f->sendMessage($this->prefix." §7[§e".str_replace("@", "", $player)."§7] §l>>§r ".str_replace($words[0], "", $msg));
$player->sendMessage($this->prefix." §7[§e".str_replace("@", "", $player)."§7] §l>>§r ".str_replace($words[0], "", $msg));
                    
                }else{
                    
$player->sendMessage($this->prefix."§c".str_replace("@", "", $player)." is not online!");
           }
           $event->setCancelled();
       }
    }
}
