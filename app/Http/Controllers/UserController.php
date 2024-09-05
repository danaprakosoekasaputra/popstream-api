<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {

  public function get_livestreams() {
    $lives = DB::select("SELECT * FROM `livestream`");
    for ($i=0; $i<sizeof($lives); $i++) {
      $live = $lives[$i];
      $lives[$i]->user = DB::select('SELECT * FROM `user` WHERE `id`='.$live->user_id)[0];
    }
    return $lives;
  }

    public function get_latest_livestreams() {
      $lives = DB::select("SELECT * FROM `livestream` ORDER BY `created_at` DESC");
      for ($i=0; $i<sizeof($lives); $i++) {
        $live = $lives[$i];
        $lives[$i]->user = DB::select('SELECT * FROM `user` WHERE `id`='.$live->user_id)[0];
      }
      return $lives;
    }

  public function login(Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    $users = DB::select("SELECT * FROM `user` WHERE `email`='".$email."' AND `password`='".$password."'");
    if (sizeof($users) > 0) {
      return array(
        'response_code' => 0,
        'data' => array(
          'user' => $users[0]
        )
      );
    } else {
      return array(
        'response_code' => -1
      );
    }
  }

  public function login2(Request $request) {
    $phone = $request->input('phone');
    $password = $request->input('password');
    $users = DB::select("SELECT * FROM `user` WHERE `phone`='".$phone."' AND `password`='".$password."'");
    if (sizeof($users) > 0) {
      return array(
        'response_code' => 0,
        'data' => array(
          'user' => $users[0]
        )
      );
    } else {
      return array(
        'response_code' => -1
      );
    }
  }

  public function login_by_phone(Request $request) {
    $phone = $request->input('phone');
    $users = DB::select("SELECT * FROM `user` WHERE `phone`='".$phone."'");
    if (sizeof($users) > 0) {
      return array(
        'response_code' => 0,
        'data' => array(
          'user' => $users[0]
        )
      );
    } else {
      return array(
        'response_code' => -1
      );
    }
  }

  public function broadcast(Request $request) {
    $userID = intval($request->input('user_id'));
    $title = $request->input('title');
    $lives = DB::select("SELECT * FROM `livestream` WHERE `user_id`=".$userID);
    $liveID = 0;
    if (sizeof($lives) > 0) {
      DB::update("UPDATE `livestream` SET `title`='".$title."'");
      $liveID = $lives[0]->id;
    } else {
      DB::insert("INSERT INTO `livestream` (`user_id`, `title`, `banner`) VALUES (".$userID.", '".$title."', 'img2.jpg')");
      $liveID = DB::getPdo()->lastInsertId();
    }
    return array(
      'response_code' => 0,
      'data' => array(
        'live_id' => $liveID
      )
    );
  }

  public function get_broadcast(Request $request) {
    $id = intval($request->input('id'));
    $lives = DB::select("SELECT * FROM `livestream` WHERE `id`=".$id);
    if (sizeof($lives) > 0) {
      $live = $lives[0];
      $users = DB::select("SELECT * FROM `user` WHERE `id`=".$live->user_id);
      if (sizeof($users) > 0) {
        $user = $users[0];
        $live->user = $user;
      }
      return array(
        'response_code' => 0,
        'data' => array(
          'live' => $live
        )
      );
    }
    return array(
      'response_code' => -1
    );
  }

  public function delete_broadcast(Request $request) {
    $id = intval($request->input('id'));
    DB::delete("DELETE FROM `livestream` WHERE `id`=".$id);
  }

  public function add_livestream_member(Request $request) {
  }

  public function get_banners(Request $request) {
    return DB::select("SELECT * FROM `banner`");
  }

  public function get_games(Request $request) {
    return DB::select("SELECT * FROM `game`");
  }

  public function get_countries(Request $request) {
    return DB::select("SELECT * FROM `country`");
  }

  public function get_nearby_countries(Request $request) {
    return DB::select("SELECT * FROM `nearby_country`");
  }

  public function get_regions(Request $request) {
    return DB::select("SELECT * FROM `region`");
  }

  public function get_popular_topics(Request $request) {
    $regionCode = $request->input('region_code');
    $languageID = $request->input('language_id');
    $topics = DB::select("SELECT * FROM `popular_topic` WHERE `region_code`='".$regionCode."'");
    if (sizeof($topics) > 0) {
      $topic = $topics[0];
      $topics = json_decode($topic->topics, true);
      for ($i=0; $i<sizeof($topics); $i++) {
        $topic = $topics[$i];
        $translations = json_decode(json_encode(DB::select("SELECT * FROM `translation` WHERE `name`='".$topic['title']."'")), true);
        if (sizeof($translations) > 0) {
          $topics[$i]['value'] = $translations[0]['value_'.$languageID];
        }
      }
      return $topics;
    }
    return array();
  }

  public function get_most_viewed_lives(Request $request) {
    $lives = DB::select("SELECT * FROM `livestream` ORDER BY `view_count` DESC");
    for ($i=0; $i<sizeof($lives); $i++) {
      $live = $lives[$i];
      $lives[$i]->user = DB::select('SELECT * FROM `user` WHERE `id`='.$live->user_id)[0];
    }
    return $lives;
  }

  public function get_lives_by_topic(Request $request) {
    $topic = $request->input('topic');
    $lives = DB::select("SELECT * FROM `livestream` WHERE `topic`='".$topic."'");
    for ($i=0; $i<sizeof($lives); $i++) {
      $live = $lives[$i];
      $lives[$i]->user = DB::select('SELECT * FROM `user` WHERE `id`='.$live->user_id)[0];
    }
    return $lives;
  }

  public function get_thai_lives(Request $request) {
    $lives = DB::select("SELECT * FROM `livestream` WHERE `user_id` IN (SELECT `id` FROM `user` WHERE `country_id`='th')");
    for ($i=0; $i<sizeof($lives); $i++) {
      $live = $lives[$i];
      $lives[$i]->user = DB::select('SELECT * FROM `user` WHERE `id`='.$live->user_id)[0];
    }
    return $lives;
  }

  public function get_tieng_viet_lives(Request $request) {
    $lives = DB::select("SELECT * FROM `livestream` WHERE `user_id` IN (SELECT `id` FROM `user` WHERE `country_id`='vn')");
    for ($i=0; $i<sizeof($lives); $i++) {
      $live = $lives[$i];
      $lives[$i]->user = DB::select('SELECT * FROM `user` WHERE `id`='.$live->user_id)[0];
    }
    return $lives;
  }

  public function get_novice_missions(Request $request) {
    $userID = $request->input('user_id');
    return DB::select("SELECT * FROM `novice_mission` WHERE `user_id`=".$userID);
  }
}
