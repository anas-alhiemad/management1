<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
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
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'expired_date' => 'required|date',
                'quantity' => 'required|integer',
                'description' => 'nullable|string',
                'type_id' => 'required|integer|exists:types,id',
                'category_id' => 'required|integer|exists:categories,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item = Item::create($validatedData);

        return response()->json($item, Response::HTTP_CREATED);
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
        $item->update($validatedData);

        return response()->json($item, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    


//*
    /**
     * Filter items by type.
     */
    /*
    public function filterByType($typeId)
    {
        $items = Item::where('type_id', $typeId)->get();
        return response()->json($items, Response::HTTP_OK);
    }

    /**
     * Filter items by category.
     
    public function filterByCategory($categoryId)
    {
        $items = Item::where('category_id', $categoryId)->get();
        return response()->json($items, Response::HTTP_OK);
    }

    /**
     * Filter items by status.
     
    public function filterByStatus($status)
    {
        $items = Item::where('status', $status)->get();
        return response()->json($items, Response::HTTP_OK);
    }
    /////// اضافي 
    /**
 * Retrieve items that are low in stock.
 
public function lowStockItems($threshold)
{
    $items = Item::where('quantity', '<', $threshold)->get();
    return response()->json($items, Response::HTTP_OK);
}

/**
 * Retrieve items that have not been updated recently.

public function outdatedItems($days)
{
    $date = \Carbon\Carbon::now()->subDays($days);
    $items = Item::where('updated_at', '<', $date)->get();
    return response()->json($items, Response::HTTP_OK);
}

/**
 * Advanced search for items.
 
public function advancedSearch(Request $request)
{
    $query = Item::query();

    if ($request->has('name')) {
        $query->where('name', 'like', '%' . $request->input('name') . '%');
    }

    if ($request->has('type_id')) {
        $query->where('type_id', $request->input('type_id'));
    }

    if ($request->has('category_id')) {
        $query->where('category_id', $request->input('category_id'));
    }

    if ($request->has('status')) {
        $query->where('status', $request->input('status'));
    }

    if ($request->has('min_quantity')) {
        $query->where('quantity', '>=', $request->input('min_quantity'));
    }

    if ($request->has('max_quantity')) {
        $query->where('quantity', '<=', $request->input('max_quantity'));
    }

    if ($request->has('updated_before')) {
        $query->where('updated_at', '<', $request->input('updated_before'));
    }

    if ($request->has('updated_after')) {
        $query->where('updated_at', '>', $request->input('updated_after'));
    }

    $items = $query->get();

    return response()->json($items, Response::HTTP_OK);
}

/**
 * Send alerts for low stock items.
 

public function sendLowStockAlerts($threshold)
{
    $items = Item::where('quantity', '<', $threshold)->get();
    foreach ($items as $item) {
        // Send alert logic here
        // For example, send an email or a notification
    }

    return response()->json(['message' => 'Alerts sent successfully'], Response::HTTP_OK);
}
 */
}
