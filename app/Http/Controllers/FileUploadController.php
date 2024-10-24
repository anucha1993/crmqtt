<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    //
    public function uploadFile(Request $request, $jobNumber)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $filename = $jobNumber . '-' . $originalFilename;
    
            $folderPath = 'oders/' . $jobNumber;
            $path = $file->storeAs($folderPath, $filename, 'public');
    
            return $path; // คืนค่าที่อยู่ของไฟล์ที่อัปโหลด
        }
    
        return null; // หรือคืนค่า null หากไม่มีการอัปโหลดไฟล์
    }

}
