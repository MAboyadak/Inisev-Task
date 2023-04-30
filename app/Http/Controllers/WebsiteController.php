<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function subscribeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'website_id' => 'required|exists:websites,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'The given data was invalid.',
                'errors'    => $validator->errors(),
            ], 422);
        }

        $website = Website::find($request->website_id);
        $user = User::find($request->user_id);

        $subscription = DB::table('subscriptions')
                            ->where('user_id',$user->id)
                            ->where('website_id',$website->id);

        if($subscription->exists()){
            return response()->json([
                'status'    => 'error',
                'message' => 'This user is already a subscriber to this website',
            ], 400);
        }

        try{
            $website->subscribers()->attach($user);

            return response()->json([
                'status'    => 'success',
                'message' => 'User has subscribed successfully',
            ], 200);
            
        }catch(\Exception $e){
            return response()->json([
                'status'    => 'error',
                'message' => 'Some Error Happened',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
