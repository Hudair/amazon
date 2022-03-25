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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Http\Request; class EnvironmentManager { private $envPath; private $envExamplePath; public function __construct() { $this->envPath = base_path("\x2e\x65\x6e\x76"); $this->envExamplePath = base_path("\56\145\156\x76\x2e\145\170\141\155\x70\x6c\x65"); } public function getEnvContent() { if (file_exists($this->envPath)) { goto RgzbX; } if (file_exists($this->envExamplePath)) { goto HXrlV; } touch($this->envPath); goto NJL4W; HXrlV: copy($this->envExamplePath, $this->envPath); NJL4W: RgzbX: return file_get_contents($this->envPath); } public function getEnvPath() { return $this->envPath; } public function getEnvExamplePath() { return $this->envExamplePath; } public function saveFileClassic(Request $input) { $message = trans("\x69\x6e\163\164\141\154\x6c\x65\x72\x5f\x6d\x65\x73\163\x61\x67\x65\163\56\x65\156\x76\151\162\x6f\156\155\145\x6e\164\x2e\163\165\143\143\145\163\x73"); try { file_put_contents($this->envPath, $input->get("\145\156\166\103\157\156\146\151\x67")); } catch (Exception $e) { $message = trans("\x69\156\x73\164\141\x6c\x6c\x65\x72\137\155\x65\163\x73\x61\147\x65\163\56\x65\x6e\x76\x69\x72\157\156\x6d\x65\x6e\x74\56\x65\162\162\157\162\163"); } return $message; } }
