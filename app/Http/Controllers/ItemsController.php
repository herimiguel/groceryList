<?php

namespace App\Http\Controllers;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $items = Auth::user()->items;
        return response()->json([
            'items' => $items
        ], 200);
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
        $request -> validate([
            'name' => 'required',
            'description' => 'required'

        ]);
        $item = $request->user()->items()->create([
            'name' => $request->name,
            'description' => $request->description
        ]);
        return response([
            'item' => $item, 
            'message' => 'items has been created'
        ]);
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
        //
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
        $request -> validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $item = $request->user()->items()->whereId($id)->update($request->all());
        return response([
            'item' => $item, 
            'message' => 'items has been created'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
         $item->delete();
         return response()->json([
            'item' => $item, 
            'message' => 'items has been deleted'
        ]); 
    }
}
