@extends('lawyers.layouts.short_layout')
@push('content')
<main class="main">
    <div class="container" style="text-align: center;">
        <div style="width:400px;display: inline-block;">

            @if(isset($mess))
                <span style="color: red">{{$mess}}</span>
            @endif

            <form method="get" action="@php echo route('actionIn_logincontroller') @endphp" >
                @csrf
                <div style=" border: 1px #6c757d solid;margin-bottom:10px;border-radius:10px;padding:15px">
                  <table style="width:100%;">
                    <tr>
                        <td style="text-align: left"><label style="font-size: 18pt;font-weight: bold">Login:</label></td>
                        <td style="text-align: center"><input type="text" name="email" style="border: 1px #adb5bd solid" ></td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><label style="font-size: 18pt;font-weight: bold" >Password:</label></td>
                        <td style="text-align: center"><input type="password" name="password" style="border: 1px #adb5bd solid;"></td>
                    </tr>
                </table>
              </div>
                <button type="submit"  style='width:100%;'  class="load-more" >Login</button>
            </form>
        </div>
    </div>
</main>
@endpush
