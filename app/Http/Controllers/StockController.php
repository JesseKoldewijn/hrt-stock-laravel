<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Resounce Controller for Stock

    public function index()
    {
        $stocks = Stock::all();

        return response()->json([
            "success" => true,
            "message" => "Stock List",
            "data" => $stocks,
        ]);
    }

    public function show($id)
    {
        $stock = Stock::find($id);

        if ($stock) {
            return response()->json([
                "success" => true,
                "message" => "Stock Detail",
                "data" => $stock,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Stock Not Found",
                "data" => "",
            ]);
        }
    }

    public function store(Request $request)
    {
        $stock = Stock::create([
            "country" => $request->country,
            "brand" => $request->brand,
            "type" => $request->type,
            "description" => $request->description,
            "stock" => $request->stock,
            "location" => $request->location,
        ]);

        if ($stock) {
            return response()->json([
                "success" => true,
                "message" => "Stock Created",
                "data" => $stock,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Stock Not Found",
                "data" => "",
            ]);
        }
    }

    public function storeMany(Request $request)
    {
        try {
            // strip all keys from the requestData which are not present in the Country model
            $rd = $request->countries;

            // create an array of arrays with the $requestData and only the keys present in the Country model
            $requestData = [];
            $fillableKeys = array_flip((new Stock())->getFillable());

            foreach ($rd as $countryData) {
                $filteredData = array_intersect_key(
                    (array) $countryData,
                    $fillableKeys
                );
                $requestData[] = $filteredData;
            }

            // Not the most efficient way to do this, but it works.
            // A better way would be to batch insert the data.

            // insert the array of arrays into the database
            foreach ($requestData as $data) {
                $country = new Stock();

                $country->name_common = $data["name_common"] ?? "";
                $country->name_official = $data["name_official"] ?? "";
                $country->name_native = $data["name_native"] ?? "";
                $country->cca2 = $data["cca2"] ?? "";
                $country->cca3 = $data["cca3"] ?? "";
                $country->currencies = json_encode(
                    $data["currencies"] ?? "",
                    JSON_UNESCAPED_LINE_TERMINATORS
                );
                $country->time_zones = $data["time_zones"] ?? "";
                $country->flags = json_encode(
                    $data["flags"] ?? "",
                    JSON_UNESCAPED_LINE_TERMINATORS
                );

                $country->save();
            }

            return response()->json([
                "success" => true,
                "message" => "Countries Created",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Countries Not Created",
                "error" => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::find($id);

        if ($stock) {
            $changedValues = [];

            // Check what values have changed and add them to the $changedValues array
            foreach ($request->all() as $key => $value) {
                // if the request value is different from the stock value
                if ($value != $stock[$key]) {
                    // add the request value to the $changedValues array
                    $changedValues[$key] = $value;
                }
            }

            $stock->update([
                "country" => $request->country,
                "brand" => $request->brand,
                "type" => $request->type,
                "description" => $request->description,
                "stock" => $request->stock,
                "location" => $request->location,
            ]);

            return response()->json([
                "success" => true,
                "message" => "Stock Updated",
                "changed_values" => $changedValues,
                "data" => $stock,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Stock Not Found",
                "data" => "",
            ]);
        }
    }
}
