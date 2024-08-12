<?php


namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{

    public function getFile($id)
    {
        $report = Report::findOrFail($id);

        if ($report->file_path && Storage::exists($report->file_path)) {
           
            $mimeType = Storage::mimeType($report->file_path);

           
            return response()->file(storage_path('app/' . $report->file_path), [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline', 
            ]);
        }

        return response()->json([
            'message' => 'File not found.'
        ], 404);
    }
    // Store a new report
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,txt,jpg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('reports');
            $validated['file_path'] = $filePath;
        }

        $report = Report::create($validated);


        $user = User::where('role', 'manager')->first();
        $notificationController = new NotificationController();
       $notificationController->sendFCMNotification($user->id,"New Category requierd permission", "A new category ($request->name) needs your approve"); // here
       $requestPending = PendingRequest::create(['requsetPending' =>json_encode( $validated),
                'type' =>'category',]);


        return response()->json([
            'message' => 'Report created successfully',
            'report' => $report
        ], 201);
    }

    // List all reports with pagination
    public function index(Request $request)
    {
        $reports = Report::paginate(10); // Paginate results, showing 10 reports per page
        return response()->json($reports);
    }

    // Show a specific report by ID
    public function show($id)
    {
        $report = Report::findOrFail($id);
        return response()->json($report);
    }

    // Update a specific report by ID
    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,txt,jpg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
       
            if ($report->file_path) {
                Storage::delete($report->file_path);
            }

         
            $filePath = $request->file('file')->store('reports');
            $validated['file_path'] = $filePath;
        }

        $report->update($validated);
        $user = User::where('role', 'manager')->first();
        $notificationController = new NotificationController();
       $notificationController->sendFCMNotification($user->id,"New Category requierd permission", "A new category ($request->name) needs your approve"); // here
       $requestPending = PendingRequest::create(['requsetPending' =>json_encode( $validated),// here
                'type' =>'category',]);

        return response()->json([
            'message' => 'Report updated successfully',
            'report' => $report
        ]);
    }

    // Delete a specific report by ID
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if ($report->file_path) {
            
            Storage::delete($report->file_path);
        }

        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }
}
