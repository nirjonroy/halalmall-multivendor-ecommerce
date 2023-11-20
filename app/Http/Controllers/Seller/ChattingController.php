<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use App\Model\Seller;
use App\Model\Shop;
use App\Model\Admin;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;
use App\Events\ChatEvent;

class ChattingController extends Controller
{
    /**
     * chatting list
     */
    public function chat(Request $request, $type)
    {
        $shop = Shop::where('seller_id', auth('seller')->id())->first();
        $shop_id = $shop->id;
        

        if ($type == 'delivery-man') {
            $last_chat = Chatting::where('seller_id', auth('seller')->id())
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

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop', 'type'));
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

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop', 'type'));
            }
        }
        elseif($type == 'admin'){

            $last_chat = Chatting::where('shop_id', $shop_id)
                ->whereNotNull(['seller_id', 'admin_id'])
                ->orderBy('created_at', 'DESC')
                ->first();
                

            if (isset($last_chat)) {
                Chatting::where(['shop_id' => $shop_id, 'admin_id' => $last_chat->admin_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('admins', 'admins.id', '=', 'chattings.admin_id')
                    ->select('chattings.*', 'admins.name', 'admins.image')
                    ->where('chattings.shop_id', $shop_id)
                    ->where('admin_id', $last_chat->admin_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                // $chattings_user = Chatting::join('admins', 'admins.id', '=', 'chattings.admin_id')
                //     ->select('chattings.*', 'admins.name', 'admins.image', 'admins.phone')
                //     ->orderBy('chattings.created_at', 'desc')
                //     ->get()
                //     ->unique('admin_id');

                $chattings_user = Admin::all();

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop', 'type'));
            }
        }

        return view('seller-views.chatting.chat', compact('last_chat', 'shop', 'type'));
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
                
            $new_sellers = Chatting::where('user_id', $request->user_id)
                          ->where('shop_id', $shop_id)->get();    

        }        
        elseif ($request->has('admin_id')) {
            $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;

            Chatting::where(['seller_id' => auth('seller')->id(), 'admin_id' => $request->admin_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('admins', 'admins.id', '=', 'chattings.admin_id')
                ->select('chattings.*', 'admins.name', 'admins.image')
                ->where('chattings.shop_id', $shop_id)
                ->where('chattings.admin_id', $request->admin_id)
                ->orderBy('created_at', 'ASC')
                ->get();
                
                $new_sellers = Chatting::where('admin_id', $request->admin_id)
                           ->where('seller_id', auth('seller')->id())
                           ->get();

        }

        return response()->json($new_sellers);
    }

    /**
     * ajax request - Store massage
     */
    public function ajax_seller_message_store(Request $request)
    {

        $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;
        
        if($request->hasFile('chat_image'))
        {

            $message = 'Test';
            $time = now();

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

elseif ($request->has('admin_id')) {

    if($request->hasFile('chat_image'))
                {
                   $originName = $request->file('chat_image')->getClientOriginalName();
               $fileName = pathinfo($originName, PATHINFO_FILENAME);
               $extension = $request->file('chat_image')->getClientOriginalExtension();
               $fileName =$fileName.time().'.'.$extension;
               $request->file('chat_image')->move(public_path('seller_to_admin_image'), $fileName);

               }

Chatting::create([
    'admin_id' => $request->admin_id,
    'seller_id' => auth('seller')->id(),
    'shop_id' => $shop_id,
    'message' => '',
    'image' => $fileName,
    'sent_by_seller' => 1,
    'seen_by_seller' => 1,
    'created_at' => now(),
]);

$dm = Admin::find($request->admin_id);
$data = [
    'title' => translate('message'),
    'description' => $request->message,
    'order_id' => '',
    'image' => '',
];

if(!empty($dm->cm_firebase_token)) {
    Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
}

event(new ChatEvent('seller-admin', auth('seller')->id(), $request->admin_id));
$fileName = asset('public/seller_to_admin_image/'.$fileName);
}

elseif (!$request->has('admin_id') && $request->has('user_id')) {
    
    if($request->hasFile('chat_image'))
                {
                    $originName = $request->file('chat_image')->getClientOriginalName();
                    $fileName = pathinfo($originName, PATHINFO_FILENAME);
                    $extension = $request->file('chat_image')->getClientOriginalExtension();
                    $fileName =$fileName.time().'.'.$extension;
                    $request->file('chat_image')->move(public_path('seller_to_customer_image'), $fileName);
               }
    
Chatting::create([
    'user_id' => $request->user_id,
    'seller_id' => auth('seller')->id(),
    'shop_id' => $shop_id,
    'message' => $request->message,
    'image' => $fileName,
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
$fileName = asset('public/seller_to_customer_image/'.$fileName);
}
        } else {
            $message = $request->message;
            $time = now();
            $fileName = '';

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

        elseif ($request->has('admin_id')) {
            Chatting::create([
                'admin_id' => $request->admin_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'created_at' => now(),
            ]);

            $dm = Admin::find($request->admin_id);
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];

            if(!empty($dm->cm_firebase_token)) {
                Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
            }

            event(new ChatEvent('seller-admin', auth('seller')->id(), $request->admin_id));
        }

        elseif (!$request->has('admin_id') && $request->has('user_id')) {
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
    
    
        
        return response()->json(['message' => $message, 'time' => $time,'fileName' => $fileName]);

    }

    public function viewContact()
    {
        return view('seller-views.contact.contact_us');
    }

    public function contactStore(Request $request)
    {
        dd($request->all());
    }

}
