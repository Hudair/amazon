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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use App\Http\Controllers\Installer\Helpers\FinalInstallManager; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; use Illuminate\Routing\Controller; class FinalController extends Controller { public function final(FinalInstallManager $finalInstall, EnvironmentManager $environment) { $finalMessages = $finalInstall->runFinal(); $finalEnvFile = $environment->getEnvContent(); return view("\x69\x6e\163\164\141\x6c\154\145\x72\56\x66\x69\x6e\151\163\150\145\x64", compact("\x66\151\156\x61\154\115\145\163\163\141\x67\145\x73", "\146\151\156\141\x6c\105\156\x76\106\151\x6c\x65")); } public function seedDemo(DatabaseManager $databaseManager) { $response = $databaseManager->seedDemoData(); return redirect()->route("\x49\156\x73\x74\141\x6c\x6c\x65\162\56\146\151\156\151\163\150"); } public function finish(InstalledFileManager $fileManager) { $finalStatusMessage = $fileManager->update(); return redirect()->to(config("\151\x6e\x73\164\141\x6c\x6c\x65\162\56\x72\145\144\151\162\x65\x63\x74\125\162\154"))->with("\155\145\163\x73\x61\147\145", $finalStatusMessage); } }
