<?php

namespace App\Http\Controllers;

use App\Models\account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::all();
        if ($accounts->count() > 0) {
            return response()->json([
                'status' => 200,
                'account' => $accounts
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Records Found'
            ], 404);
        }
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
        // Validate incoming request data
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:accounts,email',
            'password' => 'required|string|min:8|max:191',
        ]);

        // If validation fails, return error response
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages()
            ], 400);
        }

        try {
            // Sanitize the incoming data and save it to the database
            $account = new Account();
            $account->name = $request->name;
            $account->email = $request->email;
            $account->password = bcrypt($request->password); // Hash the password before saving
            $account->save();

            // Return success response with the newly created account data
            return response()->json([
                'status' => 201,
                'message' => 'Account Created Successfully',
                'account' => $account
            ], 201);
        } catch (\Exception $e) {
            // Return error response if something goes wrong during the process
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(int $accountID)
    {
        $account = account::find($accountID);

        if ($account) {
            return response()->json([
                'status' => 200,
                'account' => $account
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No account found."
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $accountId)
    {
        $account = Account::find($accountId);
    
        if ($account) {
            return response()->json([
                'status' => 200,
                'account' => $account
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'User not found.'
            ], 404);
        }
    }

    // Method to update user data
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $accountId)
{
    $account = Account::find($accountId);

    if ($account) {
        // Validate incoming request data
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:accounts,email,'.$accountId,
            'password' => 'nullable|string|min:8|max:191', // Allow password to be nullable
        ]);

        // If validation fails, return error response
        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages()
            ], 400);
        }

        // Update only the fields that are provided in the request
        $account->fill($request->only(['name', 'email']));

        // Check if a new password is provided, then hash and update it
        if ($request->has('password')) {
            $account->password = bcrypt($request->password);
        }

        // Save the updated account
        $account->save();

        return response()->json([
            'status' => 200,
            'message' => 'User data updated successfully.',
            'account' => $account
        ], 200);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'User not found.'
        ], 404);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $accountID)
    {
        $account = account::find($accountID);

        if ($account) {
            $account->delete();
            return response()->json([
                'status' => 200,
                'message' => "Account deleted succesfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No valid account to delete."
            ], 404);
        }
    }
}
