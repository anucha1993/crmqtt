<?php

use Carbon\Carbon;
use App\Models\Notifications;
use App\Models\Quotation;
use App\Models\Orders;

// For add 'active' class for activated route nav-item
if (!function_exists('active_class')) {
    function active_class($path, $active = 'active') {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}

// For checking activated route
if (!function_exists('is_active_route')) {
    function is_active_route($path) {
        return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }
}

// For add 'show' class for activated route collapse
if (!function_exists('show_class')) {
    function show_class($path) {
        return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
    }
}

if (!function_exists('countunit')) {
    function countunit() {
        $countunit = new ArrayObject();
        $countunit['0'] = (object) [
            'id' => 1,
            'name' => 'ต้น',
        ];
        $countunit['1'] = (object) [
            'id' => 2,
            'name' => 'แผ่น',
        ];
        return $countunit;
    }
}

if (!function_exists('countunitstr')) {
    function countunitstr($countunit) {
        $countunits = [
            '1' => 'ต้น',
            '2' => 'แผ่น',
        ];
        return $countunits[$countunit];
    }
}

if (!function_exists('breadcrumb')) {
    function breadcrumb($breadcrumbs) {
        $count = count($breadcrumbs) - 1;
        $html = '';
        $html .= '<div class="col-12">';
        $html .= '<nav aria-label="breadcrumb" style="float: right;">';
        $html .= '<ol class="breadcrumb">';
        $html .= '<li class="breadcrumb-item">';
        $html .= '<a href="'.url('/').'"><i class="mdi mdi-home"></i></a>';
        $html .= '</li>';
        foreach($breadcrumbs as $key => $breadcrumb) {
            if($key == $count) {
                $html .= '<li class="breadcrumb-item active">';
                $html .= $breadcrumb['title'];
                $html .= '</li>';
            } else {
                $html .= '<li class="breadcrumb-item">';
                $html .= '<a href="'.url($breadcrumb['url']).'">'.$breadcrumb['title'].'</a>';
                $html .= '</li>';
            }
        }
        $html .= '</ol>
        </nav>
    </div>';
        return $html;
    }
}

if (!function_exists('PatmentType')) {
    function PatmentType($payment) {
        if($payment == '1') {
            return 'มัดจำ';
        } else if($payment == '2') {
            return 'จ่ายหน้างาน';
        } else if($payment == '3') {
            return 'จ่ายด้วย Credit';
        } else if($payment == '4') {
            return 'จ่ายด้วย Pocket Money';
        }
    }
}

if (!function_exists('statusPaymentStr')) {
    function statusPaymentStr($status) {
        $htmlstatus = '';
        if($status == 0) {
            $htmlstatus = '<span class="dot wait"></span> รอการชำระ';
        } else if($status == 1) {
            $htmlstatus = '<span class="dot approve"></span> ชำระแล้ว';
        }
        else if($status == 2) {
            $htmlstatus = '<span class="dot info"></span> ชำระเงินมัดจำแล้ว';
        }
        else if($status == 3) {
            $htmlstatus = '<span class="dot primary"></span> รอตรวจสอบยอด';
        }
        return $htmlstatus;
    }
}

if (!function_exists('statusStr')) {
    function statusStr($status, $type) {
        $htmlstatus = '';
        if($status == 0) {
            if($type == 'delivery') {
                $htmlstatus = '<span class="dot wait"></span> กำลังดำเนินการ';
            } else {
                $htmlstatus = '<span class="dot wait"></span> รอยืนยัน';
            }
        } else if($status == 1) {
            if($type == 'delivery') {
                $htmlstatus = '<span class="dot approve"></span> จัดส่งสำเร็จ';
            } else {
                $htmlstatus = '<span class="dot approve"></span> ยืนยัน';
            }
        } else {
            $htmlstatus = '<span class="dot close"></span> ยกเลิก';
        }
        return $htmlstatus;
    }
}

if (!function_exists('statusUserstStr')) {
    function statusUserstStr($status) {
        $htmlstatus = '';
        if($status == 0) {
            $htmlstatus = '<span class="dot close"></span> ปิดใช้งาน';
        } else if($status == 1) {
            $htmlstatus = '<span class="dot approve"></span> เปิดใช้งาน';
        }
        return $htmlstatus;
    }
}

if (!function_exists('formatdate')) {
    function formatdate($date) {
        return date('d-m-Y', strtotime($date));
    }
}

if (!function_exists('TitlePagePayment')) {
    function TitlePagePayment($order_delivery_number, $order_number, $check) {
        if($check == 1) {
            return 'บิลหลัก '.$order_number;
        } else {
            return 'บิลย่อย '.$order_delivery_number;
        }
    }
}

if (!function_exists('SendNotiline')) {
    function SendNotiline($message) {
        $url = 'https://notify-api.line.me/api/notify';
        $token = 'VViob7BarOUeo0IlomZYE3m0LD4ieZzUoCxwOX7AlVA';
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token
        ];
        $fields = 'message='.$message;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}

if (!function_exists('listNoti')) {
    function listNoti() {
        $notis = Notifications::where('noti_status', 1)->where('read', 0)->orderBy('created_at', 'desc')->get();
        $html = '';
        if(count($notis) > 0) {
            foreach($notis as $noti) {
                // Your logic for displaying notifications...
            }
        }
        return $html;
    }
}

if (!function_exists('CountNotReadNoti')) {
    function CountNotReadNoti() {
        if(Auth::user()->role_id != 2) {
            $notis = Notifications::where('noti_status', 1)->where('read', 0)->orderBy('created_at', 'desc')->count();
        } else {
            $notis = Notifications::where('noti_status', 1)->where('read', 0)->where('on_vat', 1)->orderBy('created_at', 'desc')->count();
        }
        return $notis;
    }
}

if (!function_exists('baht_text')) {
    function baht_text($number, $include_unit = true, $display_zero = true) {
        // Your baht_text logic...
    }
}
