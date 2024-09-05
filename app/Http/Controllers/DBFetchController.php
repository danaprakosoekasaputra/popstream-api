<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DBFetchController extends Controller {

  public function fetch_countries() {
    $countries = json_decode(file_get_contents('userdata/countries.json'), true);
    for ($i=0; $i<sizeof($countries); $i++) {
      $country = $countries[$i];
      DB::insert("INSERT INTO `country` (`code`, `name`) VALUES ('".strtolower($country['code'])."', '".$country['name']."')");
    }
  }

  public function fetch_regions() {
    $regionsData = json_decode(file_get_contents('userdata/regions.json'), true);
    for ($i=0; $i<sizeof($regionsData); $i++) {
      $regionData = $regionsData[$i];
      $regions = DB::select("SELECT * FROM `region` WHERE `name`='".$regionData['location']."'");
      if (sizeof($regions) > 0) {
        $region = $regions[0];
        DB::update("UPDATE `country` SET `region_code`='".$region->code."' WHERE `name`='".$regionData['country']."'");
      }
    }
    return $regionsData;
  }
}
