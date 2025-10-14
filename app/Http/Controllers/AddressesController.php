<?php

namespace App\Http\Controllers;

use App\Models\addresses;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = addresses::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Addresses retrieved successfully',
            'addresses' => $addresses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'line1' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if (!$validate) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validate->errors(),
            ]);
        }

        $address = addresses::create([
            'user_id' => auth()->user()->id,
            'line1' => $request->line1,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address created successfully',
            'address' => $address,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, addresses $addresses)
    {
        $addresses->update([ 
            'user_id' => auth()->user()->id,
            'line1' => $request->line1 ?? $addresses->line1,
            'city' => $request->city ?? $addresses->city,
            'country' => $request->country ?? $addresses->country,
            'postal_code' => $request->postal_code ?? $addresses->postal_code,
            'longitude' => $request->longitude ?? $addresses->longitude,
            'latitude' => $request->latitude ?? $addresses->latitude,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'address' => $addresses,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(addresses $addresses)
    {
        $address = addresses::where('id', $addresses->id)->first();
        $address->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully',
            'address' => $address,
        ]);
    }
}
