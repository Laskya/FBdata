<?php
class Json {
  public $dir = "path_to_folder_with_messages_copy";
  public $found = array();
  public $json_decoded = array();


  public function __construct () {
    $this->search($this->dir, $this->found);
    $this->decodeJson($this->found);
  }


  public function search ($dir, &$found = array()){
    $file_ext =  " ";
    $dir_arr = explode("/", $dir);
    $last_record = end($dir_arr);

    if(strpos($last_record, ".") !== false) {
        $file_ext_arr = explode(".", $last_record);
        if (isset($file_ext_arr[1])) {
            $file_ext = $file_ext_arr[1];
            $json_file_name[] = $last_record;
        }
    }

    if ($file_ext == "json") {
        $found[] = $dir;
    } else if($file_ext == " ") {
      if($dh = opendir($dir)) {

          while (($file = readdir($dh)) !== false){
            if (($file != '.') && ($file != '..')){
              $this->search($dir . '/' . $file, $found);
            }
          }
            closedir($dh);
        }
    }
    
    return $this->found;
    return $json_file_name;
  }


  /*function decodeJson ($found = array(), &$json_decoded = array()) {
    foreach ($found as $value) {
      $json = file_get_contents($value);
      $json_decoded[] = json_decode($json, true);
    }
    return $json_decoded;
  }*/


  public function decodeJson ($found = array()) {
    foreach ($found as $key => $value) {
      $emotes = array();
      $json_decoded = array();
      $json = file_get_contents($value);
      $json_decoded = json_decode($json, true);
      $participants = count($json_decoded["participants"]);

      if($participants == 2){
        if($json_decoded["participants"][1]["name"] == "Jakub Laskowski") {
          //print_r($json_decoded["participants"]);
          $name_1_message_count = 0;
          $name_2_message_count = 0;
          $name_1 = $json_decoded["participants"][1]["name"];

          $name_2 = $json_decoded["participants"][0]["name"];
          $name_2 = str_ireplace("u00", 'x', json_encode($name_2));
          $name_2 = str_ireplace('"', '', $name_2);
          
          $name_2 = (json_encode(stripcslashes($name_2)));
          $name_2 = str_ireplace('"', '', $name_2);
          $name_2 = json_decode('"'.$name_2.'"');

          $all_messages = count($json_decoded["messages"]);

          foreach ($json_decoded["messages"] as $key => $value) {
            $sender_name = $value["sender_name"];
            if(array_key_exists("reactions", $value) && $sender_name == $name_1){
              if(!array_key_exists($value["reactions"][0]["reaction"], $emotes)){
                $emotes[$value["reactions"][0]["reaction"]] = 0;
              }
              $emotes[$value["reactions"][0]["reaction"]] += 1;
            }

            if($sender_name == $name_1){
              $name_1_message_count += 1;
            } else {
              $name_2_message_count += 1;
            }
          }

          if($name_2 != "UÅ¼ytkownik Facebooka"){
            $db = new MyDB;
            $db->executeInsert("INSERT INTO `messages`(`from_who`, `mine_messages`, `their_messages`, `all_messages`) VALUES ('$name_2',$name_1_message_count,$name_2_message_count,$all_messages)");

            print "<b>".$name_2."</b><br>";
            print "Moje wiadomości: ".$name_1_message_count."<br>";
            print "Wiadomości ".$name_2.": ".$name_2_message_count."<br>";
            print "Wszystkie wiadomości: ".$all_messages."<br> <br>";

            foreach ($emotes as $key => $value){
              $res = str_ireplace("u00", 'x', json_encode($key));
              $res = str_ireplace('"', '', $res);
              $res = (json_encode(stripcslashes($res)));
              $res = str_ireplace('"', '', $res);
              print json_decode('"'.$res.'"').": ".$value."<br>";

            

            }
            print "<br> <br> <br>";
          }
        }
      }
    }
  }
}

?>
