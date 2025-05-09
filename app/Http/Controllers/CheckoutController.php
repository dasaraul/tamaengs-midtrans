<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('products.index')->with('info', 'Silakan pilih lomba terlebih dahulu');
        }
        
        return view('checkout.index');
    }
    
    public function process(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'leader_npm' => 'required|string|max:50',
            'leader_semester' => 'required|integer|min:1|max:8',
            'leader_faculty' => 'required|string|max:255',
            'leader_phone' => 'required|string|max:20',
            'leader_email' => 'required|email|max:255',
            'member_name.*' => 'required|string|max:255',
            'member_npm.*' => 'required|string|max:50',
            'member_semester.*' => 'required|integer|min:1|max:8',
            'member_faculty.*' => 'required|string|max:255',
            'agree_terms' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if(!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('products.index')->with('error', 'Sesi pendaftaran Anda telah berakhir. Silakan pilih lomba kembali.');
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate total amount
            $total = 0;
            foreach(session('cart') as $id => $details) {
                $total += $details['price'];
            }
            
            // Create the order
            $order = new Order();
            $order->user_id = Auth::id();
            $order->status = 'pending';
            $order->total_price = $total;
            $order->team_name = $request->team_name;
            $order->institution = $request->institution;
            $order->leader_name = $request->leader_name;
            $order->leader_npm = $request->leader_npm;
            $order->leader_semester = $request->leader_semester;
            $order->leader_faculty = $request->leader_faculty;
            $order->leader_phone = $request->leader_phone;
            $order->leader_email = $request->leader_email;
            $order->save();
            
            // Add order items
            foreach(session('cart') as $id => $details) {
                $product = Product::find($id);
                
                if(!$product) {
                    throw new \Exception('Kompetisi tidak ditemukan');
                }
                
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->price = $product->price;
                $orderItem->save();
            }
            
            // Add team members
            if($request->has('member_name')) {
                for($i = 0; $i < count($request->member_name); $i++) {
                    $member = new TeamMember();
                    $member->order_id = $order->id;
                    $member->name = $request->member_name[$i];
                    $member->npm = $request->member_npm[$i];
                    $member->semester = $request->member_semester[$i];
                    $member->faculty = $request->member_faculty[$i];
                    $member->save();
                }
            }
            
            DB::commit();
            
            // Clear cart
            session()->forget('cart');
            
            // Redirect to Midtrans payment page
            return redirect()->route('payment.show', $order->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi. ' . $e->getMessage());
        }
    }
}