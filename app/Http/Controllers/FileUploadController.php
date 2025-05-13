<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
   public function uploadFile(Request $request, $jobNumber)
{
    if ($request->hasFile('file')) {
        try {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $filename = $jobNumber . '-' . $originalFilename;

            // ✅ แก้จาก 'oders/' → 'orders/'
            $folderPath = 'orders/' . $jobNumber;

            // ตรวจสอบ path ก่อนบันทึก
            if (!$jobNumber || !$filename || !$folderPath) {
                Log::error("Invalid path: jobNumber={$jobNumber}, filename={$filename}, folderPath={$folderPath}");
                return null;
            }

            $path = $file->storeAs($folderPath, $filename, 'public');

            if (!$path) {
                Log::error("Failed to store file to path: {$folderPath}/{$filename}");
                return null;
            }

            return $path;

        } catch (\Exception $e) {
            Log::error("File upload error: " . $e->getMessage());
            return null;
        }
    }

    Log::warning("No file uploaded in request.");
    return null;
}


}
