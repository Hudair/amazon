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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use Exception; use Illuminate\Routing\Controller; use Illuminate\Support\Facades\DB; class DatabaseController extends Controller { private $databaseManager; public function __construct(DatabaseManager $databaseManager) { $this->databaseManager = $databaseManager; } public function database() { if ($this->checkDatabaseConnection()) { goto Q2zaC; } return redirect()->back()->withErrors(["\x64\141\x74\x61\x62\x61\x73\x65\x5f\143\x6f\156\156\145\x63\x74\151\157\x6e" => trans("\x69\156\x73\164\141\154\154\x65\x72\137\x6d\145\163\x73\x61\x67\145\163\56\145\156\x76\151\162\x6f\156\155\x65\x6e\x74\56\167\151\x7a\x61\x72\x64\x2e\x66\157\x72\x6d\x2e\144\x62\137\x63\x6f\156\156\145\x63\164\151\157\x6e\x5f\x66\x61\151\154\145\x64")]); Q2zaC: ini_set("\155\141\170\137\x65\170\x65\x63\x75\164\151\x6f\156\137\164\x69\155\x65", 600); $response = $this->databaseManager->migrateAndSeed(); return redirect()->route("\111\x6e\163\164\x61\x6c\154\145\x72\x2e\x66\151\x6e\x61\154")->with(["\155\x65\x73\x73\141\147\145" => $response]); } private function checkDatabaseConnection() { try { DB::connection()->getPdo(); return true; } catch (Exception $e) { return false; } } }
