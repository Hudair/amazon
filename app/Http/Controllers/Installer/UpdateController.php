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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; use Illuminate\Routing\Controller; class UpdateController extends Controller { use \App\Http\Controllers\Installer\Helpers\MigrationsHelper; public function welcome() { return view("\x69\x6e\163\164\141\x6c\x6c\145\162\56\x75\160\x64\x61\164\x65\56\x77\145\x6c\x63\x6f\155\145"); } public function overview() { $migrations = $this->getMigrations(); $dbMigrations = $this->getExecutedMigrations(); return view("\151\x6e\x73\164\141\x6c\154\x65\162\56\x75\x70\x64\141\x74\145\x2e\157\x76\145\x72\166\x69\145\x77", ["\156\x75\155\142\145\162\117\146\x55\x70\x64\141\x74\x65\163\x50\145\x6e\144\151\156\147" => count($migrations) - count($dbMigrations)]); } public function database() { $databaseManager = new DatabaseManager(); $response = $databaseManager->migrateAndSeed(); return redirect()->route("\x4c\x61\162\x61\x76\145\x6c\x55\x70\144\x61\x74\145\162\72\72\146\x69\x6e\141\x6c")->with(["\155\x65\163\x73\141\147\145" => $response]); } public function finish(InstalledFileManager $fileManager) { $fileManager->update(); return view("\x69\156\163\164\141\x6c\154\145\x72\x2e\x75\x70\x64\x61\x74\x65\56\x66\151\x6e\151\163\150\145\x64"); } }
