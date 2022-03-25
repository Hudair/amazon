<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.1   |
    |              on 2022-03-02 18:17:37              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
/*
* Copyright (C) Incevio Systems, Inc - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Munna Khan <help.zcart@gmail.com>, September 2018
*/
 namespace App\Http\Controllers\Installer\Helpers; class PermissionsChecker { protected $results = []; public function __construct() { $this->results["\x70\145\x72\155\x69\163\x73\151\x6f\156\163"] = []; $this->results["\x65\162\162\157\162\163"] = null; } public function check(array $folders) { foreach ($folders as $folder => $permission) { if (!($this->getPermission($folder) >= $permission)) { goto jxwyn; } $this->addFile($folder, $permission, true); goto x96k_; jxwyn: $this->addFileAndSetErrors($folder, $permission, false); x96k_: IpzQN: } UnMMn: return $this->results; } private function getPermission($folder) { return substr(sprintf("\45\157", fileperms(base_path($folder))), -4); } private function addFile($folder, $permission, $isSet) { array_push($this->results["\x70\x65\162\155\151\163\163\x69\x6f\x6e\163"], ["\146\x6f\154\x64\x65\x72" => $folder, "\160\145\162\155\x69\163\x73\151\x6f\x6e" => $permission, "\x69\163\123\x65\x74" => $isSet]); } private function addFileAndSetErrors($folder, $permission, $isSet) { $this->addFile($folder, $permission, $isSet); $this->results["\145\162\162\x6f\162\x73"] = true; } }
