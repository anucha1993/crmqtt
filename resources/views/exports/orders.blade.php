<table>
    <thead>
        <tr>
            <th> ลำดับ </th>
            <th> วันที่สร้างใบเสนอราคา </th>
            <th> เลขที่บิล </th>
            <th> ชื่อร้าน </th>
            <th> เลขประจำตัวผู้เสียภาษี </th>
            <th> ผู้ติดต่อ </th>
            <th> เบอร์โทรศัพท์ </th>
            <th> อีเมล </th>
            <th> ยอดสั่งซื้อ </th>
            <th> ประเภทการจ่าย </th>
            <th> สถานะจัดส่ง </th>
            <th> สถานะชำระเงิน </th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
    @foreach($datas as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ date('d/m/Y H:i:s',strtotime($data->quotation_created_at)) }}</td>
            <td>{{ $data->order_number }}</td>
            <td>{{ $data->name_store  }}</td>
            <td>{{intval($data->text_number_vat) }}</td>
            <td>{{$data->name_customer }}</td>
            <td>{{$data->phone }}</td>
            <td>{{$data->customer_mail }}</td>
            <td>{{number_format($data->total,2)}}</td>
            <td>{{PatmentType($data->payment_type)}}</td>
            <td>{{strip_tags(statusStr($data->status_send,'delivery'))}}</td>
            <td>{{strip_tags(statusPaymentStr($data->status_payment))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
