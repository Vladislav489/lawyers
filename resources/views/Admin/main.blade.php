@extends('Admin.layouts.layout')
@php $setting_site = getSetting('site_setting');@endphp


@push('content')
    <style>
        .setting-col{margin:0px;padding:0px}
        .setting-col table tr td{
            padding: 10px;
        }

    </style>
    <h1>Main Dashbord</h1>
    <div class="row">
        <div class="col-9">

        </div>
        <div class="col-3 setting-col">
          <table class="">
              <tr>
                  <td>
                      <label class="switch">
                          <input type="checkbox"
                                 @checked(( $setting_site &&  $setting_site["includeFromHeadToDown"] == "true")?true:false)
                                 name="includeFromHeadToDown">
                          <span class="slider round"></span>
                      </label>
                  </td>
                  <td>
                      <span> Построени фронта в класическом варианте
                          <span title = "отключает или включает. javascript ожидпние полной загрузки или выполнять по загругрузке кода" class="help">?</span></span>
                  </td>
              </tr>
          </table>
        </div>
    </div>
    <script>
        $("input[name=includeFromHeadToDown]").click(function(){
            var sendParams = {'site_setting':{}};
            sendParams['key'] ='site_setting';
            sendParams['value'] = {'includeFromHeadToDown':$(this).is(':checked')}
            page__.sendData('{{route__('actionSetSetting_admin_mainstay_backcontroller')}}',sendParams);
        });
    </script>
@endpush
