<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    // Resounce Controller for Country

    public function index()
    {
        $countries = Country::all();

        return response()->json([
            "success" => true,
            "message" => "Country List",
            "data" => $countries,
        ]);
    }

    public function show($id)
    {
        $country = Country::find($id);

        if ($country) {
            return response()->json([
                "success" => true,
                "message" => "Country Detail",
                "data" => $country,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Country Not Found",
                "data" => "",
            ]);
        }
    }

    public function store(Request $request)
    {
        $country = Country::create([
            "name_common" => $request->name_common,
            "name_official" => $request->name_official,
            "name_native" => $request->name_native,
            "cca2" => $request->cca2,
            "cca3" => $request->cca3,
            "currencies" => $request->currencies,
            "time_zones" => $request->time_zones,
            "flags" => $request->flags,
        ]);

        if ($country) {
            return response()->json([
                "success" => true,
                "message" => "Country Created",
                "data" => $country,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Country Not Created",
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
            $fillableKeys = array_flip((new Country())->getFillable());

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
                $country = new Country();

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
        $country = Country::find($id);

        if ($country) {
            $changedValues = [];

            // Check what values have changed and add them to the $changedValues array
            foreach ($request->all() as $key => $value) {
                // if the request value is different from the stock value
                if ($value != $country[$key]) {
                    // add the request value to the $changedValues array
                    $changedValues[$key] = $value;
                }
            }

            $country->update([
                "name_common" => $request->name_common,
                "name_official" => $request->name_official,
                "name_native" => $request->name_native,
                "cca2" => $request->cca2,
                "cca3" => $request->cca3,
                "currencies" => $request->currencies,
                "time_zones" => $request->time_zones,
                "flags" => $request->flags,
            ]);

            return response()->json([
                "success" => true,
                "message" => "Country Updated",
                "changed_values" => $changedValues,
                "data" => $country,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Country Not Found",
                "data" => "",
            ]);
        }
    }

    public function destroy($id)
    {
        $country = Country::find($id);

        if ($country) {
            $country->delete();

            return response()->json([
                "success" => true,
                "message" => "Country Deleted",
                "data" => $country,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Country Not Found",
                "data" => "",
            ]);
        }
    }

    /**
     * Search Country by Name
     * Path: /api/country/search/{name}
     */
    public function search($name)
    {
        $country = Country::where(
            "name_common",
            "like",
            "%" . $name . "%"
        )->get();

        if ($country) {
            return response()->json([
                "success" => true,
                "message" => "Country Search Result",
                "data" => $country,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Country Not Found",
                "data" => "",
            ]);
        }
    }
}
