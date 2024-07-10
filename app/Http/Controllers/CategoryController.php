<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $categories = Category::all();
        return response()->json($categories, Response::HTTP_OK);
    }

    public function indexAvailable()
    {
        $categories = Category::where('available', true)->get();
        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Display a listing of the unavailable categories.
     */
    public function indexUnAvailable()
    {
        $categories = Category::where('available', false)->get();
        return response()->json($categories, Response::HTTP_OK);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is not needed for API
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable',
        ]);
        if($request->parent_id!=null){
            $parent= Category::find($request->parent_id);
            if($parent){
             $category = Category::create($validated);
             $user = User::where('role', 'manager')->first();
     $notificationController = new NotificationController();
             $notificationController->sendFCMNotification($user->id,"New Category requierd permission", "A new category ($request->name) needs your approve");
     
             return response()->json($category, Response::HTTP_CREATED);
            }
             else{
                 return response()->json('parernt not exit', Response::HTTP_NOT_FOUND);
             }
        }else{
            $category = Category::create($validated);
            $user = User::where('role', 'manager')->first();
    $notificationController = new NotificationController();
            $notificationController->sendFCMNotification($user->id,"New Category requierd permission", "A new category ($request->name) needs your approve");
    
            return response()->json($category, Response::HTTP_CREATED);
        }


      
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // This method is not needed for API
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        $category->available= false;
        $category->reqiestedName= $request->name;;
        $category->save();

        $notificationController = new NotificationController();
        $notificationController->sendFCMNotification('admin_user_id',"updated Category requierd permission", "update category ($request->name) needs your approve");

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Accept the category request.
     */
    public function acceptRequest(Category $category)
    {
        if ($category===null) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        $category->available = true;
        $user = User::where('role', 'warehourseguard')->first();
        try {
            if ($category->reqiestedName == null) { // create
                $category->save();
                $notificationController = new NotificationController();
                $notificationController->sendFCMNotification($user->id, "Category approved successfully", "New category ({$category->name}) added to list");

                return response()->json(null, Response::HTTP_NO_CONTENT);
            }

            $category->name = $category->reqiestedName;
            $category->reqiestedName = null;
            $category->save();

           $notificationController = new NotificationController();
            $notificationController->sendFCMNotification($user->id, "Category updated successfully", "Update category ({$category->name}) done");

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to send notification'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reject the category request.
     */
    public function rejectRequest( Category $category)
    {
        if (!$category) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        $user = User::where('role', 'warehourseguard')->first();
        try {
            $notificationController = new NotificationController();

            if ($category->reqiestedName == null) { // create
                $category->delete();
                $notificationController->sendFCMNotification($user->id, "New Category rejected", "New category ({$category->name}) has been rejected");

                return response()->json(null, Response::HTTP_NO_CONTENT);
            }

            $category->reqiestedName = null;
            $category->available = false;
            $category->save();

            $notificationController->sendFCMNotification($user->id, "Update Category rejected", "Update category ({$category->name}) rejected");

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to send notification'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    


    /**
     * Filter items by category.
     */
    // public function filterItems(Request $request, Category $category)
    // {
    //     $items = Item::where('category_id', $category->id);

    //     // Apply additional filters if provided
    //     if ($request->has('status')) {
    //         $items->where('status', $request->input('status'));
    //     }

    //     if ($request->has('min_quantity')) {
    //         $items->where('quantity', '>=', $request->input('min_quantity'));
    //     }

    //     if ($request->has('max_quantity')) {
    //         $items->where('quantity', '<=', $request->input('max_quantity'));
    //     }

    //     $items = $items->get();

    //     return response()->json($items, Response::HTTP_OK);
    // }
}

