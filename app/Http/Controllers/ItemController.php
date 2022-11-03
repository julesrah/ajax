<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Stock;
use View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $items = Item::orderBy('item_id', 'ASC')->get();
            return response()->json($items);
        }
    }
    public function getItem (){
        return view('Item.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Item;
        $item ->description = $request->description;
        $item->sell_price = $request->sell_price;
        $item->cost_price = $request->cost_price;
        $item->title = $request->title;
        $files = $request->file('uploads');
        $item->imagePath = 'images/'.$files->getClientOriginalName();
        $item->save();

        Storage::put('public/images/'.$files->getClientOriginalName(),file_get_contents($files));

        return response()->json(["success" => "item created successfully.", "item" => $item, "status" => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $items = Item::find($id);
        return response()->json($items);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if ($request->ajax()) {
        $item = Item::find($id);
        $item = $item->update($request->all());

        $item = Item::find($id);
        return response()->json($item);
        // } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $items = Item::findOrFail($id);
        $items->delete();
        return response()->json(["success" => "item deleted successfully.",
             "status" => 200]);
    }

    public function postCheckout(Request $request){

        // $items = json_decode($request->json()->all());
        $items = json_decode($request->getContent(),true);
        // Log::info(print_r($request->getContent()));
        Log::info(print_r($items, true));
          try {
              DB::beginTransaction();
              $order = new Order();
              $customer =  Customer::find(3);
              // dd($cart->items);
            $customer->orders()->save($order);
              // dd($cart->items);
            // Log::info(print_r($order->orderinfo_id, true));
            foreach($items as $item) {
              // Log::info(print_r($item, true));
               $id = $item['item_id'];
               // Log::info(print_r($, true));
               $order->items()->attach($order->orderinfo_id,['quantity'=> $item['quantity'],'item_id'=>$id]);
               // Log::info(print_r($order, true));
               $stock = Stock::find($id);
               $stock->quantity = $stock->quantity - $item['quantity'];
               $stock->save();
            }
            
          }
          catch (\Exception $e) {
              DB::rollback();
              return response()->json(array('status' => 'Order failed','code'=>409,'error'=>$e->getMessage()));
              }
      
          DB::commit();
          return response()->json(array('status' => 'Order Success','code'=>200,'order id'=>$order->orderinfo_id));
      
          }//end postcheckout
}
