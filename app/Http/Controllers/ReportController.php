<?php


namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;



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
    
        // Create the report
        $report = Report::create($validated);
    
        // Notify the manager
        $user = User::where('role', 'manager')->first();
        $notificationController = new NotificationController();
        $notificationController->sendFCMNotification(
            $user->id,
            "New Report Submitted",
            "A new report titled '{$report->title}' has been submitted by the warehouse manager."
        );
    
        return response()->json([
            'message' => 'Report created successfully and notification sent to manager.',
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
