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
 namespace App\Http\Controllers\Installer; use App\Http\Controllers\Installer\Helpers\RequirementsChecker; use Illuminate\Routing\Controller; class RequirementsController extends Controller { protected $requirements; public function __construct(RequirementsChecker $checker) { $this->requirements = $checker; } public function requirements() { $phpSupportInfo = $this->requirements->checkPHPversion(config("\151\x6e\163\x74\141\154\154\145\x72\56\x63\x6f\162\145\56\155\151\156\x50\150\160\x56\145\x72\x73\x69\157\156"), config("\151\x6e\163\x74\141\x6c\154\145\162\x2e\x63\157\162\x65\x2e\x6d\141\170\120\x68\x70\x56\145\162\x73\x69\x6f\156")); $requirements = $this->requirements->check(config("\151\x6e\x73\164\x61\154\x6c\145\162\56\x72\145\x71\165\151\x72\x65\155\x65\x6e\164\x73")); return view("\151\x6e\x73\x74\x61\154\x6c\x65\162\x2e\x72\x65\161\x75\151\x72\x65\x6d\145\x6e\x74\163", compact("\162\145\161\x75\151\162\145\x6d\145\156\x74\x73", "\160\x68\160\x53\x75\160\x70\157\x72\x74\x49\156\x66\157")); } }
