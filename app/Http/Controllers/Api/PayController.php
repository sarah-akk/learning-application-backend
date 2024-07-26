<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PayController extends Controller
{

    public function checkout(Request $request)
    {
        try {
            $user = $request->user();
            $token = $user->token;
            $course_id=$request->id;

            Stripe::setApiKey('sk_test_51OlprDDAa99VseJJpcruOnWR1iiHmbSR45nMST9lg3ijKcyzKNkfzGaG5IxinZCUYAVtKZPfeu3PCLh759xMXuS700Ga097vwa');

            $course_result = Course::where('id','=',$course_id)->first();
            if (empty($course_result)){
                return response()->json([
                    'msg' => 'course not exist'
                ], 400);
            }

            $orderMap=[];
            $orderMap['course_id']=$course_id;
            $orderMap['token']=$token;
            $orderMap['status']=1;


            $orderRes = Order::where($orderMap)->first();
            if(!empty($orderRes)){
                return response()->json([
                    'msg' => 'you already bought this course!',
                    'data'=>$orderRes
                ], 400);
            }


            $YOUR_DOMAIN=env('APP_URL');
            $map =[];
            $map['token']=$token;
            $map['course_id']=$course_id;
            $map['total_amount']= $course_result->price;
            $map['status']=0;
            $map['created_at']=Carbon::now();

            $orderNum = Order::insertGetId($map);



            $checkOutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $course_result->name,
                            'description' => $course_result->description,
                        ],
                        'unit_amount' => intval($course_result->price * 100), // Amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success',
                'cancel_url' => $YOUR_DOMAIN . '/cancel',
                'metadata' => [
                    'order_num' => $orderNum,
                    'user_token' => $token,
                ],
            ]);

            return response()->json([
                'msg' => 'Successfully bought course!',
                 'data' =>$checkOutSession->url,

            ], 200);


        } catch (\Throwable $th) {
            return response()->json(
                    ['error' => $th->getMessage(),
                    ], 500);
        }
    }
}
