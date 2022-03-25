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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Support\Facades\Artisan; use Symfony\Component\Console\Output\BufferedOutput; class FinalInstallManager { public function runFinal() { $outputLog = new BufferedOutput(); $this->generateKey($outputLog); $this->publishVendorAssets($outputLog); return $outputLog->fetch(); } private static function generateKey($outputLog) { try { if (!config("\151\x6e\x73\164\141\154\154\145\162\56\x66\x69\x6e\141\154\56\153\145\x79")) { goto KRk7n; } Artisan::call("\x6b\145\x79\x3a\x67\145\x6e\x65\162\141\x74\x65", ["\x2d\x2d\146\x6f\162\x63\x65" => true], $outputLog); KRk7n: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function publishVendorAssets($outputLog) { try { if (!config("\151\156\x73\164\x61\154\154\x65\x72\56\x66\x69\x6e\141\x6c\56\160\x75\x62\154\151\163\150")) { goto R5H5Y; } Artisan::call("\x76\145\156\x64\157\x72\x3a\x70\x75\x62\154\151\163\x68", ["\55\x2d\x61\x6c\x6c" => true], $outputLog); R5H5Y: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function response($message, $outputLog) { return ["\x73\164\x61\164\x75\x73" => "\x65\162\x72\x6f\x72", "\155\145\x73\x73\141\x67\145" => $message, "\144\x62\117\x75\x74\160\x75\x74\x4c\x6f\147" => $outputLog->fetch()]; } }
