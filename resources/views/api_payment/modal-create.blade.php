<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">แจ้งชำระเงิน &nbsp; <span id="pocket-grand"></span> <span
                class="pull-right text-success" id="total-calculate"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form action="{{ route('payment.store') }}" enctype="multipart/form-data" id="paymnet-store" method="POST">
            @csrf
            @method('POST')
            <div class="modal-body">

                <input type="text" name="order_id" value="{{ $order->id }}">
                <input type="text" name="order_number" value="{{ $order->order_number }}">
                <input type="text" name="order_delivery_id" value="">

                <label for="">ประเภทการชำระเงิน <span class="text-danger"> *</span></label>


                <select name="payment_type" id="payment-type" class="form-select form-control mb-3" required>
                    <option value="">--เลือกการจ่ายเงิน--</option>
                    @forelse ($paymentType as $item)
                        <option data-method="{{ $item->payment_type_method }}"
                            data-pocket-money="{{ $item->payment_pocket_money }}"
                            @if ($item->payment_type_id === $order->payment_type) selected @endif value="{{ $item->payment_type_id }}">
                            {{ $item->payment_type_name }}</option>
                    @empty
                    @endforelse
                </select>


                <label>จำนวนเงินที่ชำระ <span class="text-danger"> *</span></label>
                <input type="number" name="total" class="form-control mb-3 payment-total" step="0.01" value="">


                <label>วันที่ชำระ <span class="text-danger"> *</span></label>
                <input type="datetime-local" name="date_playment" class="form-control mb-3 "value="{{ date('Y-m-d H:m:s') }}">

                <label for="">แนบหลักฐานกาารชำระ</label><br>
                <input type="file" name="file">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary" form="paymnet-store">บันทึก</button>
            </div>
        </form>
    </div>

</div>
