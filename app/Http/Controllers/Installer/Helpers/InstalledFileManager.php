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
 namespace App\Http\Controllers\Installer\Helpers; class InstalledFileManager { public function create() { $installedLogFile = storage_path("\x69\x6e\163\164\141\154\154\145\x64"); $dateStamp = date("\131\x2f\x6d\x2f\x64\x20\x68\x3a\151\x3a\163\x61"); if (!file_exists($installedLogFile)) { goto CAHuo; } $message = trans("\151\x6e\x73\x74\x61\154\154\145\162\137\155\145\163\163\x61\147\145\x73\x2e\165\160\x64\141\x74\145\162\x2e\154\157\147\56\163\165\143\143\x65\x73\x73\137\x6d\x65\163\163\x61\147\x65") . $dateStamp; file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX); goto DfyrC; CAHuo: $message = trans("\151\x6e\x73\164\141\154\154\x65\x72\x5f\x6d\145\163\163\x61\147\x65\163\56\151\x6e\163\x74\x61\154\x6c\145\144\56\163\165\x63\143\x65\163\163\137\x6c\x6f\147\137\x6d\145\163\x73\x61\147\145") . $dateStamp . "\12"; file_put_contents($installedLogFile, $message); DfyrC: return $message; } public function update() { return $this->create(); } }
