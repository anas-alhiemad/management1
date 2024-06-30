<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentsController extends Controller
{
    public function addDocuments(Request $request,$id)
    {

        $imagePath = null ;
        $pdfPath = null ;
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_pdf' => 'required|mimes:pdf|max:10000',
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public_upload');
        }

        if ($request->hasFile('file_pdf')) {
            $pdfPath = $request->file('file_pdf')->store('cv', 'public_files');
        }

        Document::create([
            'beneficiary_id' => $id,
            'image' => $imagePath,
            'file_pdf' => $pdfPath,
        ]);

        return response()->json(['message' =>'File created successfully.']);
    }



    public function showDocuments($id)
    {
        $document = Document::where('beneficiary_id',$id)->get();
        return response()->json(['message' =>$document]);
    }


    public function updateDocuments(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_pdf' => 'nullable|mimes:pdf|max:10000',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public_upload');
            $document->image = $imagePath;
        }

        if ($request->hasFile('file_pdf')) {
            $pdfPath = $request->file('file_pdf')->store('cv', 'public_files');
            $document->file_pdf = $pdfPath;
        }

        $document->save();

        return response()->json(['message' => 'Document updated successfully.', 'document' => $document]);
    }

    public function destroyDocuments($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully.']);
    }


}
