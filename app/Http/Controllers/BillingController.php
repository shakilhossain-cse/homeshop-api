<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    //
    public function index(Request $request)
    {
        // Get the authenticated user's ID
        $user_id = auth()->user()->id;

        // Check if the user has a contact record
        $contact = Billing::where('user_id', $user_id)->first();

        // Return a JSON response with the contact data or empty columns
        if ($contact) {
            return response()->json([
                'firstName' => $contact->firstName,
                'lastName' => $contact->lastName,
                'companyName' => $contact->companyName,
                'country' => $contact->country,
                'streetAddress' => $contact->streetAddress,
                'townCity' => $contact->townCity,
                'zipCode' => $contact->zipCode,
                'phoneNumber' => $contact->phoneNumber,
                'email' => $contact->email,
            ],200);
        } else {
            return response()->json([
                'firstName' => '',
                'lastName' => '',
                'companyName' => '',
                'country' => '',
                'streetAddress' => '',
                'townCity' => '',
                'zipCode' => '',
                'phoneNumber' => '',
                'email' => '',
            ],200);
        }
    }

    public function upsert(Request $request)
    {
        // Validate the request data
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'companyName' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'streetAddress' => 'required|string|max:255',
            'townCity' => 'required|string|max:255',
            'zipCode' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
        ]);

        // Get the authenticated user's ID
        $user_id = auth()->user()->id;

        $data = $request->only([
            'firstName',
            'lastName',
            'companyName',
            'country',
            'streetAddress',
            'townCity',
            'zipCode',
            'phoneNumber',
            'email',
        ]);

        Billing::updateOrCreate(
            ['user_id' => $user_id],
            $data
        );

        $response_data = ["massage" => "Your Data Update Successfully"];
        return response()->json($response_data, 201);
    }
}
