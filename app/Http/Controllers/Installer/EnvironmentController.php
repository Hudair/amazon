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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use Illuminate\Http\Request; use Illuminate\Routing\Controller; use Illuminate\Routing\Redirector; use Validator; class EnvironmentController extends Controller { protected $EnvironmentManager; public function __construct(EnvironmentManager $environmentManager) { $this->EnvironmentManager = $environmentManager; } public function environmentMenu() { return view("\151\156\x73\164\x61\154\154\145\x72\56\x65\x6e\x76\x69\162\x6f\x6e\x6d\x65\x6e\x74"); } public function environmentWizard() { } public function environmentClassic() { $envConfig = $this->EnvironmentManager->getEnvContent(); return view("\151\x6e\163\x74\x61\x6c\154\145\x72\x2e\145\156\166\151\162\x6f\156\155\145\x6e\x74\55\143\x6c\x61\x73\x73\x69\x63", compact("\145\x6e\x76\103\157\x6e\x66\151\147")); } public function saveClassic(Request $input, Redirector $redirect) { $message = $this->EnvironmentManager->saveFileClassic($input); return $redirect->route("\111\156\x73\164\141\154\x6c\145\162\56\145\156\166\151\162\x6f\x6e\155\x65\x6e\164\x43\x6c\141\163\x73\151\143")->with(["\x6d\145\x73\x73\x61\147\x65" => $message]); } }
