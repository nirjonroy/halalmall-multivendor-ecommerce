<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\SellerWallet;
use App\Model\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Order;
use App\Model\Brand;
use App\Model\Product;
use App\Model\OrderDetail;
use App\Model\AdminWallet;

class WithdrawController extends Controller
{
    public function w_request(Request $request)
    {
        $wallet = SellerWallet::where('seller_id', auth()->guard('seller')->user()->id)->first();
        if (($wallet->total_earning) >= Convert::usd($request['amount']) && $request['amount'] > 1) {
            DB::table('withdraw_requests')->insert([
                'seller_id' => auth()->guard('seller')->user()->id,
                'amount' => Convert::usd($request['amount']),
                'transaction_note' => null,
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $wallet->total_earning -= Convert::usd($request['amount']);
            $wallet->pending_withdraw += Convert::usd($request['amount']);
            $wallet->save();
            Toastr::success('Withdraw request has been sent.');
            return redirect()->back();
        }

        Toastr::error('invalid request.!');
        return redirect()->back();
    }

    public function close_request($id)
    {
        $withdraw_request = WithdrawRequest::find($id);
        $wallet = SellerWallet::where('seller_id', auth()->guard('seller')->user()->id)->first();
        
        if (isset($withdraw_request) && isset($wallet) && $withdraw_request->approved == 0) {
            $wallet->total_earning += Convert::usd($withdraw_request['amount']);
            $wallet->pending_withdraw -= Convert::usd($withdraw_request['amount']);
            $wallet->save();
            $withdraw_request->delete();
            Toastr::success('Request closed!');
        } else {
            Toastr::error('Invalid request');
        }

        return back();
    }
  
   public function order_stats_data()
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $pending = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'pending'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $confirmed = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'confirmed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $processing = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'processing'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $out_for_delivery = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'out_for_delivery'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $delivered = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])
            ->where(['order_status' => 'delivered'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $canceled = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'canceled'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $returned = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'returned'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $failed = Order::where(['seller_is' => 'seller'])->where(['seller_id' => auth('seller')->id()])->where(['order_status' => 'failed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();

        $data = [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'processing' => $processing,
            'out_for_delivery' => $out_for_delivery,
            'delivered' => $delivered,
            'canceled' => $canceled,
            'returned' => $returned,
            'failed' => $failed
        ];

        return $data;
    }

    public function status_filter(Request $request)
    {
        session()->put('withdraw_status_filter', $request['withdraw_status_filter']);
        return response()->json(session('withdraw_status_filter'));
    }

    public function list()
    {
        
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_requests = WithdrawRequest::with(['seller'])
            ->where(['seller_id'=>auth('seller')->id()])
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')
            ->paginate(Helpers::pagination_limit());
      
        $top_sell = OrderDetail::with(['product'])
            ->whereHas('product', function ($query){
                $query->where(['added_by'=>'seller']);
            })
            ->where(['seller_id'=>auth('seller')->id()])
            ->select('product_id', DB::raw('SUM(qty) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();
      
        $most_rated_products = Product::where(['user_id'=>auth('seller')->id(), 'added_by'=>'seller'])
            ->rightJoin('reviews', 'reviews.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->select(['product_id',
                DB::raw('AVG(reviews.rating) as ratings_average'),
                DB::raw('count(*) as total')
            ])
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();
      
        $data = self::order_stats_data();
        $data['order'] = Order::count();
        $data['brand'] = Brand::count();

        
        $admin_wallet = SellerWallet::where('seller_id', auth('seller')->id())->first();
        $data['total_earning'] = $admin_wallet->total_earning ?? 0;
        $data['withdrawn'] = $admin_wallet->withdrawn ?? 0;
        $data['commission_given'] = $admin_wallet->commission_given ?? 0;
        $data['pending_withdraw'] = $admin_wallet->pending_withdraw ?? 0;
        $data['delivery_charge_earned'] = $admin_wallet->delivery_charge_earned ?? 0;
        $data['collected_cash'] = $admin_wallet->collected_cash ?? 0;
        $data['total_tax_collected'] = $admin_wallet->total_tax_collected ?? 0;
        

        return view('seller-views.withdraw.list', compact('data', 'withdraw_requests'));
    }

    public function add_claim(Request $request)
    {
        $withdraw = WithdrawRequest::find($request->id);

        $withdraw->update(['transaction_note'=>$request->transaction_note]);

        Toastr::success('Withdraw Request Claim Added!');
        return back();
    }
}
