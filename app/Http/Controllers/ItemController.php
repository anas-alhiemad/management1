<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\PendingRequest;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Validator;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::all();
        return response()->json($items, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If you are using an API, this method might not be necessary.
        // This is usually for returning a view in web applications.
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            $validator =Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'expired_date' => 'required|date',
                'quantity' => 'required|integer',
                'description' => 'nullable|string',
                'type_id' => 'required|integer|exists:types,id',
                'category_id' => 'required|integer|exists:categories,id',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
     try{
         $user = User::where('role', 'manager')->first();
         $notificationController = new NotificationController();
         $notificationController->sendFCMNotification($user->id,"New Item requierd permission", "A new item ($request->name) needs your approve");
        }catch(e){}

        $requestPending = PendingRequest::create(['requsetPending' =>json_encode( $validator->validated()),
        'type' =>'item',]);


          return response()->json(['message' =>  'Request submitted successfully.']);
    }
    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return response()->json($item, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        // If you are using an API, this method might not be necessary.
        // This is usually for returning a view in web applications.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'expired_date' => 'sometimes|required|date',
                'quantity' => 'sometimes|required|integer',
                'description' => 'nullable|string',
                'type_id' => 'sometimes|required|integer|exists:types,id',
                'category_id' => 'sometimes|required|integer|exists:categories,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item = Item::findOrFail($id);
        $requestPendingData = $validatedData;
        $requestPendingData['id'] = $item->id;
        $requestPending = PendingRequest::create(['requsetPending' =>json_encode($requestPendingData),
        'type' =>'item',]);

        try{
            $user = User::where('role', 'manager')->first();
            $notificationController = new NotificationController();
            $notificationController->sendFCMNotification($user->id,"Update Item requierd permission", "item ($request->name) needs your approve");
           }catch(e){}
        return response()->json($item, Response::HTTP_OK);
    }

    /**w
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }





    public function filterByType($typeId)
    {
        $items = Item::where('type_id', $typeId)->get();
        return response()->json($items, Response::HTTP_OK);
    }


    public function filterByCategory($categoryId)
    {
        $items = Item::where('category_id', $categoryId)->get();
        return response()->json($items, Response::HTTP_OK);
    }



    public function filterByStatus($status)
    {
        $items = Item::where('status', $status)->get();
        return response()->json($items, Response::HTTP_OK);
    }
    /////// اضافي

public function lowStockItems($threshold)
{
    $items = Item::where('quantity', '<', $threshold)->get();
    return response()->json($items, Response::HTTP_OK);
}


public function outdatedItems($days)
{
    $date = \Carbon\Carbon::now()->subDays($days);
    $items = Item::where('updated_at', '<', $date)->get();
    return response()->json($items, Response::HTTP_OK);
}


public function advancedSearch(Request $request)
{
    $query = Item::query();

    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->input('name') . '%');
    }

    if ($request->filled('type_id')) {
        $query->where('type_id', $request->input('type_id'));
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->input('category_id'));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }
    if ($request->filled('available')) {
        $query->where('available', $request->input('available'));
    }

    if ($request->filled('min_quantity')) {
        $query->where('quantity', '>=', $request->input('min_quantity'));
    }

    if ($request->filled('max_quantity')) {
        $query->where('quantity', '<=', $request->input('max_quantity'));
    }

    if ($request->filled('updated_before')) {
        $query->where('updated_at', '<', $request->input('updated_before'));
    }

    if ($request->filled('updated_after')) {
        $query->where('updated_at', '>', $request->input('updated_after'));
    }

    $items = $query->get();

    return response()->json($items, Response::HTTP_OK);
}


public function sendLowStockAlerts($threshold)
{
    $items = Item::where('quantity', '<', $threshold)->get();
    foreach ($items as $item) {
        // Send alert logic here
        // For example, send an email or a notification
    }

    return response()->json(['message' => 'Alerts sent successfully'], Response::HTTP_OK);

}}
