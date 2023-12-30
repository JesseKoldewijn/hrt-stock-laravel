<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CountryController;
use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

// Path: /api/countries - (Resource: Country) - (non-Resource routes)
Route::prefix("countries")->group(function () {
    Route::get("search/{name}", [CountryController::class, "search"]);
    Route::post("storeMany", [CountryController::class, "storeMany"]);
});
// Path: /api/countries - (Resource: Country)
Route::apiResources([
    "countries" => CountryController::class,
]);
// <- End of Path: /api/countries - (Resource: Country)

// Path: /api/stocks - (Resource: Stock)
Route::prefix("stocks")->group(function () {
    Route::get("search/{name}", [StockController::class, "search"]);
    Route::post("storeMany", [StockController::class, "storeMany"]);
});
Route::apiResources([
    "stocks" => StockController::class,
]);
