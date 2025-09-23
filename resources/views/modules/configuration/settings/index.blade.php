@extends('layout')
@section('content')
    <div class="row align-items-center mt-5">
        <div class="col-md-10 col-11 mx-auto">
            <form class="needs-validation" novalidate method="post" action="{{ route('configuration.settings.save') }}">
                @csrf
                <div class="card shadow mb-5">
                    <h5 class="card-header text-muted"><i class="fas fa-cogs"></i> Configuration Settings</h5>
                    <div class="card-body">
                        <div class="alert d-none" id="alert-box-form" role="alert"></div>

                        <h6 class="card-title text-left bg-info text-white p-1"><i class="fas fa-calendar-check mr-2"></i> Online Appointment Allocated Slots Per Timeslot</h6>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online">Default Online</label>
                                        <input id="settings-online" class="form-control f-c-border" name="settings[appointment][online]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-amendment">Amendment</label>
                                        <input id="settings-online-amendment" class="form-control f-c-border" name="settings[appointment][online][amendment]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.amendment') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-certification">Certification</label>
                                        <input id="settings-online-certification" class="form-control f-c-border" name="settings[appointment][online][certification]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.certification') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-claim-stub">Claim Stub</label>
                                        <input id="settings-online-claim-stub" class="form-control f-c-border" name="settings[appointment][online][claim_stub]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.claim_stub') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-drop-box">Drop Box</label>
                                        <input id="settings-online-drop-box" class="form-control f-c-border" name="settings[appointment][online][drop_box]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.drop_box') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-mayors-clearance">Mayors Clearance</label>
                                        <input id="settings-online-mayors-clearance" class="form-control f-c-border" name="settings[appointment][online][mayors_clearance]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.mayors_clearance') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-renewal">Renewal</label>
                                        <input id="settings-online-renewal" class="form-control f-c-border" name="settings[appointment][online][renewal]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.renewal') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-online-retirement">Retirement</label>
                                        <input id="settings-online-retirement" class="form-control f-c-border" name="settings[appointment][online][retirement]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.online.retirement') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="card-title text-left bg-info text-white p-1"><i class="fas fa-walking mr-2"></i> Walk-In Appointment Allocated Slots Per Timeslot</h6>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-priority">Priority</label>
                                        <input id="settings-walk-in-priority" class="form-control f-c-border" name="settings[appointment][walk_in_priority]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in_priority') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-non-priority">Non-priority</label>
                                        <input id="settings-walk-in-non-priority" class="form-control f-c-border" name="settings[appointment][walk_in_non_priority]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in_non_priority') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-amendment">Amendment</label>
                                        <input id="settings-walk-in-amendment" class="form-control f-c-border" name="settings[appointment][walk_in][amendment]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.amendment') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-certification">Certification</label>
                                        <input id="settings-walk-in-certification" class="form-control f-c-border" name="settings[appointment][walk_in][certification]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.certification') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-claim-stub">Claim Stub</label>
                                        <input id="settings-walk-in-claim-stub" class="form-control f-c-border" name="settings[appointment][walk_in][claim_stub]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.claim_stub') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-drop-box">Drop Box</label>
                                        <input id="settings-walk-in-drop-box" class="form-control f-c-border" name="settings[appointment][walk_in][drop_box]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.drop_box') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-mayors-clearance">Mayors Clearance</label>
                                        <input id="settings-walk-in-mayors-clearance" class="form-control f-c-border" name="settings[appointment][walk_in][mayors_clearance]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.mayors_clearance') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-renewal">Renewal</label>
                                        <input id="settings-walk-in-renewal" class="form-control f-c-border" name="settings[appointment][walk_in][renewal]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.renewal') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-walk-in-retirement">Retirement</label>
                                        <input id="settings-walk-in-retirement" class="form-control f-c-border" name="settings[appointment][walk_in][retirement]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.retirement') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="settings-walk-in-grace-period">Grace Period (mins.)</label>
                                        <input id="settings-walk-in-grace-period" class="form-control f-c-border" name="settings[appointment][walk_in][grace_period]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.walk_in.grace_period') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="card-title text-left bg-info text-white p-1"><i class="fas fa-calendar-check mr-2"></i> Weekends Allowed Appointments</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="card-title text-left bg-info text-white p-1"><i class="fas fa-cog mr-2"></i> Others</h6>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="settings-weekend-saturday">Saturday</label>
                                    <div class="input-group">
                                        @php
                                            // Saturday settings
                                            list($satStartDate, $satEndDate) = explode(' - ', \App\Helpers\SettingHelper::get('appointment.weekend.saturday'));
                                            $satStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $satStartDate)->format('m/d/Y');
                                            $satEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $satEndDate)->format('m/d/Y');
                                        @endphp
                                        <input id="settings-weekend-saturday" class="use-daterangepicker form-control f-c-border" name="settings[weekend][saturday]" value="{{ $satStartDate . ' - ' . $satEndDate }}" readonly required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text f-c-border"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="settings-weekend-sunday">Sunday</label>
                                    <div class="input-group">
                                        @php
                                            // Sunday settings
                                            list($sunStartDate, $sunEndDate) = explode(' - ', \App\Helpers\SettingHelper::get('appointment.weekend.sunday'));
                                            $sunStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $sunStartDate)->format('m/d/Y');
                                            $sunEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $sunEndDate)->format('m/d/Y');
                                        @endphp
                                        <input id="settings-weekend-sunday" class="use-daterangepicker form-control f-c-border" name="settings[weekend][sunday]" value="{{ $sunStartDate . ' - ' . $sunEndDate }}" readonly required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text f-c-border"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings-no-bin-filter">No Bin Filter</label>
                                        <input id="settings-no-bin-filter" class="form-control f-c-border" name="settings[appointment][no_bin_filter]" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ \App\Helpers\SettingHelper::get('appointment.no_bin_filter') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success float-right btn-block shadow-sm">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
