<?php

namespace Wovosoft\SettingsManager\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wovosoft\SettingsManager\Facades\Settings;

class SettingsController extends Controller
{
    public static function routes()
    {
        Route::post("SettingsManager/set", '\\' . __CLASS__ . '@set')->name('SettingsManager.Set');
        Route::post("SettingsManager/get", '\\' . __CLASS__ . '@get')->name('SettingsManager.Get');
        Route::post("SettingsManager/all", '\\' . __CLASS__ . '@all')->name('SettingsManager.All');
        Route::post("SettingsManager/all_grouped", '\\' . __CLASS__ . '@allGrouped')->name('SettingsManager.All.Grouped');
        Route::post("SettingsManager/delete", '\\' . __CLASS__ . '@delete')->name('SettingsManager.Delete');
    }

    public function set(Request $request)
    {
        try {
            $status = Settings::set(
                $request->post("key"),
                $request->post("value"),
                $request->post("group"),
                $request->post("type"),
                $request->post("options"),
                $request->post("getModel") ?? false,
                $request->post("id") ?? null
            );
            return response()->json([
                "status" => $status,
                "msg" => $status ? "Successfully Done" : "Failed to perform the operation",
                "type" => $status ? "success" : "warning",
                "title" => $status ? "Success" : "Failed"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "status" => false,
                "msg" => $exception->getMessage(),
                "title" => "Failed",
                "type" => "danger",
                "file" => $exception->getFile(),
                "line" => $exception->getLine()
            ], $exception->getCode());
        }
    }

    public function get(Request $request)
    {
        return Settings::get($request->post('key'), $request->post('group'));
    }

    public function all(Request $request)
    {
        return Settings::all($request->post('getModel'), $request->post('grouped'));
    }

    public function allGrouped(Request $request)
    {
        return Settings::allGrouped($request->post('getModel') ?? false);
    }

    public function delete(Request $request)
    {
        try {
            $status = Settings::delete($request->post("id"));
            return response()->json([
                "status" => $status,
                "msg" => $status ? "Successfully Done" : "Failed to perform the operation",
                "type" => $status ? "success" : "warning",
                "title" => $status ? "Success" : "Failed"
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "status" => false,
                "msg" => $exception->getMessage(),
                "title" => "Failed",
                "type" => "danger",
                "file" => $exception->getFile(),
                "line" => $exception->getLine()
            ], $exception->getCode());
        }
    }
}
