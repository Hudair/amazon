<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CustomCssRequest;

class CustomCssController extends Controller
{
    use Authorizable;

    /**
     * Display list of custom css
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('admin.customcss.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(CustomCssRequest $request)
    {
        $css = strip_tags($request->input('theme_custom_css'), "\n\r");

        if (update_option_table_record('theme_custom_styling', $css)) {
            return redirect()->route('admin.appearance.custom_css')
                ->with('success', trans('messages.custom_css_updated'));
        }

        return redirect()->route('admin.appearance.custom_css')
            ->with('error', trans('messages.failed'));
    }
}
