<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\PendingRequest;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use App\Jobs\SendExpiryNotification;
class ItemController extends Controller
{

      /**
     * Get items that have less than one week to expire.
     */
    public function getExpiringSoonItems(Request $request)
    {
        $oneWeekFromNow = Carbon::now()->addWeek();
        $paginate = $request->input('paginate', 50); // Default to 50 if not provided
        $paginate = ($paginate == 0) ? 50 : $paginate; // Set to 50 if 0 is provided

        $expiringSoonItems = Item::where('expired_date', '<=', $oneWeekFromNow)
                                 ->where('expired_date', '>', Carbon::now())
                                 ->paginate($paginate);

        return response()->json($expiringSoonItems, Response::HTTP_OK);
    }

    /**
     * Get items that are already expired.
     */
    public function getExpiredItems(Request $request)
    {
        $paginate = $request->input('paginate', 50); // Default to 50 if not provided
        $paginate = ($paginate == 0) ? 50 : $paginate; // Set to 50 if 0 is provided

        $expiredItems = Item::where('expired_date', '<=', Carbon::now())->paginate($paginate);

        return response()->json($expiredItems, Response::HTTP_OK);
    }

       /**
     * Check items for expiration within one week and send notifications.
     */
    public function checkExpiringItems()
    {
        $oneWeekFromNow = Carbon::now()->addWeek();
        $now = Carbon::now();

        // Use chunking to process items in batches
        Item::where(function($query) use ($now, $oneWeekFromNow) {
                // Include items that are expiring within the next week
                $query->where('expired_date', '<=', $oneWeekFromNow)
                      ->where('expired_date', '>', $now);
            })
            ->orWhere(function($query) use ($now) {
                // Include items that are already expired
                $query->where('expired_date', '<=', $now);
            })
            ->where('notified_for_expiry', false)
            ->chunk(100, function ($items) {
                foreach ($items as $item) {
                    SendExpiryNotification::dispatch($item);
                }
            });

        return response()->json(['message' => 'Notifications sent for expiring and expired items.'], Response::HTTP_OK);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->input('paginate', 50); // Default to 50 if not provided
        $paginate = ($paginate == 0) ? 50 : $paginate; // Set to 50 if 0 is provided

        $items = Item::paginate($paginate);
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
                'expired_date' => 'date',
                'quantity' => 'required|integer',
                'minimum_quantity' => 'integer',
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
                'expired_date' => 'sometimes|date',
                'minimum_quantity' => 'integer',
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

    $paginate = $request->input('paginate', 50); // Default to 50 if not provided
    $paginate = ($paginate == 0) ? 50 : $paginate; // Set to 50 if 0 is provided

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

    $items = $query->paginate($paginate);

    return response()->json($items, Response::HTTP_OK);
}


 /**
     * Export items to Excel.
     */

     public function exportToExcel(Request $request)
     {
         $fields = $request->input('fields', [
             'id',
             'name',
             'description',
             'quantity',
             'minimum_quantity', // Add this field
             'status',
             'available',
             'expired_date',
             'type_id',
             'category_id',
             'created_at',
             'updated_at',
         ]);

         return Excel::download(new ItemsExport($fields), 'items.xlsx');
     }


     public function importFromExcel(Request $request)
     {

         $file = $request->file('excel_file');
         Excel::import(new ItemsImport, $file);

         return response()->json(null, Response::HTTP_CREATED);
     }

     public function cunsumeItem(Request $request, $id)
     {
         try {
             // Validate the request
             $validator = Validator::make($request->all(), [
                 'quantityCunsume' => 'required|integer',
             ]);

             if ($validator->fails()) {
                 return response()->json($validator->errors(), 422);
             }
         } catch (ValidationException $e) {
             return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
         }

         // Find the item by ID
         $item = Item::findOrFail($id);

         try {
             // Check if the quantity to consume is greater than the available quantity
             if ($request->quantityCunsume > $item->quantity) {
                 return response()->json(['error' => 'The quantity requested is above the existing quantity.'], 400);
             }

             // Decrease the item quantity
             $item->quantity -= $request->quantityCunsume;
             $item->save();

             // Check if the item quantity is less than the minimum quantity
             if ($item->quantity < $item->minimum_quantity) {
                 // Get the user with the role of 'warehouseguard'
                 $user = User::where('role', 'warehouseguard')->first();

                 if ($user) {
                     // Send notification to the user
                     $notificationController = new NotificationController();
                     $notificationController->sendFCMNotification(
                         $user->id,
                         "Low on quantity",
                         "Item ({$item->name}) need more supply"
                     );
                 }
             }
         } catch (Exception $e) {
             return response()->json(['error' => 'An error occurred while updating the item.'], 500);
         }

         return response()->json($item, Response::HTTP_OK);
     }}
