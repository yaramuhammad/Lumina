<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function addAddress(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'apartment' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'type' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $address = Address::create([
            'user_id' => Auth::id(),
            'city' => $request->city,
            'street' => $request->street,
            'district' => $request->district,
            'building' => $request->building,
            'floor' => $request->floor,
            'apartment' => $request->apartment,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'type' => $request->type,
            'notes' => $request->notes,
        ]);

        return response()->json($address, 201);
    }

    public function getAddresses()
    {
        $addresses = Auth::user()->addresses;

        return response()->json($addresses, 200);
    }

    public function removeAddress($id)
    {
        $address = Address::findOrFail($id);

        if ($address->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $address->delete();

        return response()->json(['message' => 'Address removed successfully'], 200);
    }
}
