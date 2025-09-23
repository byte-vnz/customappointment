<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\BinSchedule;
use App\Http\Requests\SettingRequest;
use Carbon\Carbon;

class SettingHelper
{
    /**
     * Sets the value of a setting.
     *
     * @param array $data
     * @param string $type
     * @return mixed
     */
    public static function set($data, $type = 'default')
    {
        // Prepare the data
        $settings = [
            'value' => $data['value'],
            'name' => $data['name']
        ];

        // conditions if no matching model exists then create new entry
        $conditions = ['key' => $data['key']];
        if (!empty($data['date_from']) && !empty($data['date_to'])) {
            $conditions['date_from'] = $data['date_from'];
            $conditions['date_to'] = $data['date_to'];
        }

        switch ($type) {
            case 'default':
                // Default Settings
                return Setting::updateOrCreate($conditions, $settings);
                break;
            case 'bin':
                // Bin Schedule Settings
                return BinSchedule::updateOrCreate($conditions, $settings);
                break;
        }
    }

    /**
     * Gets the value of a setting by key.
     *
     * @param $key
     * @param null $default
     * @return string|null
     */
    public static function get($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return $default;
        }
    }

    /**
     * Process a setting save request.
     *
     * @param SettingRequest $request
     */
    public static function processRequest(SettingRequest $request)
    {
        foreach ($request->except('_token') as $parentKey => $child) {
            foreach ($child as $settingKey => $settingValue) {
                foreach ($settingValue as $key => $value) {
                    // Check if there's a third subsection
                    if (is_array($value)) {
                        foreach ($value as $keyValue => $thirdSubSectionValue) {
                            $keyName = "{$settingKey}.{$key}.{$keyValue}";
                            // Check for saturday or sunday settings
                            if ($keyName == "appointment.weekend.saturday" || $keyName == "appointment.weekend.sunday") {
                                list($startDate, $endDate) = explode(' - ', $thirdSubSectionValue);
                                $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
                                $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');

                                $thirdSubSectionValue = $startDate . ' - ' . $endDate;
                            }

                            Setting::updateOrCreate(['key' => $keyName], ['value' => $thirdSubSectionValue]);
                        }
                    } else {
                        Setting::updateOrCreate(['key' => "{$settingKey}.{$key}"], ['value' => $value]);
                    }
                }
            }
        }
    }
}
