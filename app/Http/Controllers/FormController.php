<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    //

    public function printForm(Request $request)
    {
        // ค้นหาฟอร์มแรกในฐานข้อมูล
        $form = Form::first(); // หรือคุณอาจต้องการค้นหาฟอร์มตามเงื่อนไขที่เหมาะสม
    
        if ($form) {
            // อัปเดตจำนวนครั้งที่พิมพ์ โดยใช้คอลัมน์ print_count แทน count
            $form->print_count += 1; // เพิ่มค่าตัวนับ
            $form->save(); // บันทึกการเปลี่ยนแปลง
    
            return response()->json(['message' => 'Form printed successfully!', 'print_count' => $form->print_count]);
        }
    
        return response()->json(['message' => 'Form not found!'], 404);
    }
    
    
    

}
