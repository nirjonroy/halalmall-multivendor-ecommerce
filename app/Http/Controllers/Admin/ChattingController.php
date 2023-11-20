<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;
use App\Events\ChatEvent;
use App\Model\Admin;
use App\Model\Seller;
use App\Model\Shop;

class ChattingController extends Controller
{

    public function chat(Request $request, $type)
    {
        $admin = Admin::find(auth('admin')->id())->first();
        $admin_id = auth('admin')->id();
        
        $seller_id = 23;
        $shop = Shop::where('seller_id', $seller_id)->first();
        
        $shop_id = $shop->id;

        if ($type == 'delivery-man') {
            $last_chat = Chatting::where('seller_id', $seller_id)
                ->whereNotNull(['delivery_man_id', 'seller_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['seller_id'=> auth('seller')->id(), 'delivery_man_id'=> $last_chat->delivery_man_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                    ->where('chattings.seller_id', auth('seller')->id())
                    ->where('delivery_man_id', $last_chat->delivery_man_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
                    ->where('chattings.seller_id', auth('seller')->id())
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('delivery_man_id');

                return view('admin-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop', 'type'));
            }

        }
        elseif($type == 'customer'){
            
            $last_chat = Chatting::where('shop_id', $shop_id)
                ->whereNotNull(['user_id', 'seller_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['shop_id' => $shop_id, 'user_id' => $last_chat->user_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                    ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
                    ->where('chattings.shop_id', $shop_id)
                    ->where('user_id', $last_chat->user_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                $chattings_user = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                    ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image', 'users.phone')
                    ->where('chattings.shop_id', $shop_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('user_id');

                return view('admin-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop', 'type'));
            }
        }
        elseif($type == 'seller'){              
            
           
            $last_chat = Chatting::where('shop_id', $shop_id)
                ->whereNotNull(['seller_id', 'admin_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['shop_id' => $shop_id, 'admin_id' => $admin->id])->update([
                    'seen_by_admin' => 1
                ]);

                // $chattings = Chatting::join('sellers', 'sellers.id', '=', 'chattings.seller_id')
                //     ->select('chattings.*', 'sellers.f_name', 'sellers.l_name', 'sellers.image')
                //     ->where('chattings.shop_id', $shop_id)
                //     ->where('admin_id', $admin->id)
                //     ->orderBy('chattings.created_at', 'desc')
                //     ->get();

                $chattings = Chatting::all();

                    $chattings_user = Chatting::join('sellers', 'sellers.id', '=', 'chattings.seller_id')
                    ->select('chattings.*', 'sellers.f_name', 'sellers.l_name', 'sellers.image', 'sellers.phone')
                    ->where('admin_id', $admin_id)
                    ->whereNotNull('seller_id')
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()   
                    ->unique('seller_id'); 
                    

                // $chattings_user = Chatting::join('sellers', 'sellers.id', '=', 'chattings.seller_id')
                //     ->select('chattings.*', 'sellers.f_name', 'sellers.l_name', 'sellers.image', 'sellers.phone')
                //     ->orderBy('chattings.created_at', 'desc')
                //     ->get()
                //     ->unique('seller_id');
                // $chattings_user = Seller::join('chattings','chattings.seller_id', '=', 'sellers.id')
                // ->select('sellers.*','chattings.*')->orderBy('chattings.created_at','desc')->get()->unique('seller_id');
               // $chattings_user = Seller::all();

                return view('admin-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'admin', 'type'));
            }
        }

        return view('admin-views.chatting.chat', compact('last_chat', 'admin', 'type'));
    }

    /**
     * ajax request - get message by delivery man and customer
     */
    public function ajax_message_by_user(Request $request)
    {
        if ($request->has('delivery_man_id')) {
            Chatting::where(['seller_id' => auth('seller')->id(), 'delivery_man_id' => $request->delivery_man_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                ->where('chattings.seller_id', auth('seller')->id())
                ->where('chattings.delivery_man_id', $request->delivery_man_id)
                ->orderBy('created_at', 'ASC')
                ->get();

        }
        elseif ($request->has('user_id')) {
            $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;

            Chatting::where(['seller_id' => auth('seller')->id(), 'user_id' => $request->user_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
                ->where('chattings.shop_id', $shop_id)
                ->where('chattings.user_id', $request->user_id)
                ->orderBy('created_at', 'ASC')
                ->get();

        }
        elseif ($request->has('seller_id')) {

            $shop_id = Shop::find($request->seller_id)->id;

            Chatting::where(['seller_id' => $request->seller_id, 'admin_id' => auth('admin')->id()])
                ->update([
                    'seen_by_admin' => 1
                ]);


            $chattings_user = Chatting::join('sellers', 'sellers.id', '=', 'chattings.seller_id')
                    ->select('chattings.*', 'sellers.f_name', 'sellers.l_name', 'sellers.image', 'sellers.phone')
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('seller_id');

            $sellers = Chatting::join('sellers', 'sellers.id', '=', 'chattings.seller_id')
                ->select('chattings.*', 'sellers.f_name', 'sellers.l_name', 'sellers.image', 'sellers.phone')
                ->where('chattings.shop_id', $shop_id)
                ->where('chattings.admin_id', auth('admin')->id())
                ->orderBy('created_at', 'ASC')
                ->get();

            $new_sellers = Chatting::where('seller_id', $shop_id)->where('admin_id', auth('admin')->id())->get();
            // dd($new_sellers);die();

            // $data = Chatting::where('id', 708)->first();
            // dd($sellers);die();

        }
        return response()->json($new_sellers);
    }

    /**
     * ajax request - Store massage
     */
    public function ajax_seller_message_store(Request $request)
    {

        $shop_id = Shop::where('seller_id', $request->seller_id)->first()->id;

        $imgResult = $request->imgData;

        if($request->hasFile('chat_image'))
        {

             $message = 'Test';
             $time = now();
             $fileName='';


            if ($request->has('delivery_man_id')) {

                Chatting::create([
                    'delivery_man_id' => $request->delivery_man_id,
                    'seller_id' => auth('seller')->id(),
                    'shop_id' => $shop_id,
                    'message' => $request->message,
                    'sent_by_seller' => 1,
                    'seen_by_seller' => 1,
                    'created_at' => now(),
                ]);

                $dm = DeliveryMan::find($request->delivery_man_id);
                if(!empty($dm->fcm_token)) {
                    $data = [
                        'title' => translate('message'),
                        'description' => $request->message,
                        'order_id' => '',
                        'image' => '',
                    ];
                    Helpers::send_push_notif_to_device($dm->fcm_token, $data);
                }

                event(new ChatEvent('seller-deliveryman', auth('seller')->id(), $request->delivery_man_id));

            }


            elseif ($request->has('seller_id')) {

                if($request->hasFile('chat_image'))
                {
                   $originName = $request->file('chat_image')->getClientOriginalName();
                   $fileName = pathinfo($originName, PATHINFO_FILENAME);
                   $extension = $request->file('chat_image')->getClientOriginalExtension();
                   $fileName =$fileName.time().'.'.$extension;
                   $request->file('chat_image')->move(public_path('admin_to_seller_image'), $fileName);
                }

               Chatting::create([
                   'seller_id' => $request->seller_id,
                   'admin_id' => auth('admin')->id(),
                   'shop_id' => $shop_id,
                   'message' => '',
                   'image' => $fileName,
                   'sent_by_admin' => 1,
                   'seen_by_admin' => 1,
                   'created_at' => now(),
               ]);


               $dm = Seller::find($request->seller_id);
               $data = [
                   'title' => translate('message'),
                   'description' => $request->message,
                   'order_id' => '',
                   'image' => '',
               ];

               if(!empty($dm->cm_firebase_token)) {
                   Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
               }

               event(new ChatEvent('seller-admin', auth('admin')->id(), $request->seller_id));

            }


            elseif (!$request->has('seller_id') && $request->has('user_id')) {
                Chatting::create([
                    'user_id' => $request->user_id,
                    'seller_id' => auth('seller')->id(),
                    'shop_id' => $shop_id,
                    'message' => $request->message,
                    'sent_by_seller' => 1,
                    'seen_by_seller' => 1,
                    'created_at' => now(),
                ]);

                $dm = User::find($request->user_id);
                $data = [
                    'title' => translate('message'),
                    'description' => $request->message,
                    'order_id' => '',
                    'image' => '',
                ];
                if(!empty($dm->cm_firebase_token)) {
                    Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
                }

                event(new ChatEvent('seller-customer', auth('seller')->id(), $request->user_id));
            }


        } else {

            $message = $request->message;
            $time = now();
            $fileName= '';


        if ($request->has('delivery_man_id')) {

            Chatting::create([
                'delivery_man_id' => $request->delivery_man_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'created_at' => now(),
            ]);

            $dm = DeliveryMan::find($request->delivery_man_id);
            if(!empty($dm->fcm_token)) {
                $data = [
                    'title' => translate('message'),
                    'description' => $request->message,
                    'order_id' => '',
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($dm->fcm_token, $data);
            }

            event(new ChatEvent('seller-deliveryman', auth('seller')->id(), $request->delivery_man_id));

        }

        elseif ($request->has('seller_id')) {
            Chatting::create([
                'seller_id' => $request->seller_id,
                'admin_id' => auth('admin')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'sent_by_admin' => 1,
                'seen_by_admin' => 1,
                'created_at' => now(),
            ]);

            $dm = Seller::find($request->seller_id);
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];

            if(!empty($dm->cm_firebase_token)) {
                Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
            }

            event(new ChatEvent('seller-admin', auth('admin')->id(), $request->seller_id));
        }

        elseif (!$request->has('seller_id') && $request->has('user_id')) {
            Chatting::create([
                'user_id' => $request->user_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'created_at' => now(),
            ]);

            $dm = User::find($request->user_id);
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];
            if(!empty($dm->cm_firebase_token)) {
                Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
            }

            event(new ChatEvent('seller-customer', auth('seller')->id(), $request->user_id));
        }

        }


        $fileName=asset('public/admin_to_seller_image/'.$fileName);

        return response()->json(['message' => $message, 'time' => $time,'image'=>$fileName]);
    }

    /**
     * chatting list
     */
    // public function chat(Request $request)
    // {
    //     $last_chat = Chatting::where('admin_id', 0)
    //         ->whereNotNull(['delivery_man_id', 'admin_id'])
    //         ->orderBy('created_at', 'DESC')
    //         ->first();

    //     if (isset($last_chat)) {
    //         Chatting::where(['admin_id'=>0, 'delivery_man_id'=> $last_chat->delivery_man_id])->update([
    //             'seen_by_admin' => 1
    //         ]);


    //         $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
    //             ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
    //             ->where('chattings.admin_id', 0)
    //             ->where('delivery_man_id', $last_chat->delivery_man_id)
    //             ->orderBy('chattings.created_at', 'desc')
    //             ->get();

    //         $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
    //             ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
    //             ->where('chattings.admin_id', 0)
    //             ->orderBy('chattings.created_at', 'desc')
    //             ->get()
    //             ->unique('delivery_man_id');

    //         return view('admin-views.delivery-man.chat', compact('chattings', 'chattings_user', 'last_chat'));
    //     }

    //     return view('admin-views.delivery-man.chat', compact('last_chat'));
    // }

    // /**
    //  * ajax request - get message by delivery man
    //  */
    // public function ajax_message_by_delivery_man(Request $request)
    // {

    //     Chatting::where(['admin_id' => 0, 'delivery_man_id' => $request->delivery_man_id])
    //         ->update([
    //             'seen_by_admin' => 1
    //         ]);

    //     $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
    //         ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
    //         ->where('chattings.admin_id', 0)
    //         ->where('chattings.delivery_man_id', $request->delivery_man_id)
    //         ->orderBy('created_at', 'ASC')
    //         ->get();

    //     return response()->json($sellers);
    // }

    // /**
    //  * ajax request - Store massage for deliveryman
    //  */
    // public function ajax_admin_message_store(Request $request)
    // {
    //     if ($request->message == '') {
    //         Toastr::warning('Type Something!');
    //         return response()->json(['message' => 'type something!']);
    //     }

    //     $message = $request->message;
    //     $time = now();

    //     Chatting::create([
    //         'delivery_man_id' => $request->delivery_man_id,
    //         'admin_id' => 0,
    //         'message' => $request->message,
    //         'sent_by_admin' => 1,
    //         'seen_by_admin' => 1,
    //         'created_at' => now(),
    //     ]);

    //     $dm = DeliveryMan::find($request->delivery_man_id);

    //     if(!empty($dm->fcm_token)) {
    //         $data = [
    //             'title' => translate('message'),
    //             'description' => $request->message,
    //             'order_id' => '',
    //             'image' => '',
    //         ];
    //         Helpers::send_push_notif_to_device($dm->fcm_token, $data);
    //     }

    //     return response()->json(['message' => $message, 'time' => $time]);
    // }
}
