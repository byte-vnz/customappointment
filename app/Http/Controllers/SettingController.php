<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers;
use App\Http\Requests\SettingRequest;
use App\Helpers\SettingHelper;

class SettingController extends Controller
{
    /**
     * Shows settings configuration
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('modules.configuration.settings.index');
    }

    /**
     * Saves configuration settings.
     *
     * @param SettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveConfigurationSettings(SettingRequest $request)
    {
        // Save all
        SettingHelper::processRequest($request);

        // We're done
        session()->flash('success', 'Configuration settings successfully updated.');
        return back();
    }
}
