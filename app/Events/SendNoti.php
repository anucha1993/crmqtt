<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Notifications;
use App\Models\Quotation;
use App\Models\Orders;
use App\Models\User;

class SendNoti implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $html;
    public $check;
    public function __construct()
    {
        $noti = Notifications::where('noti_status',0)->orderBy('created_at','desc')->first();
        $html = '';
        $check = 'all';
        if($noti->noti_type == 1)
        {
            $quotation = Quotation::where('id',$noti->item_id)->first();
            if($noti->items_status == 0)
            {
                $html .= '<a href="javascript:void(0)" data-noti="'.$noti->id.'" class="dropdown-noti clickread">';
                    $html .= '<div class="preview-thumbnail noti">';
                        $html .= '<img class="img-noti" src="'.url('image/ic_noti_message.png').'">';
                    $html .= '</div>';
                    $html .= '<div class="preview-item-content noti">';
                        $html .= '<p class="small-text mb-2">สร้างใบเสนอราคา '.$quotation->quotation_number.'</p>';
                        $html .= '<p class="small-text mb-2">ยอดรวมใบเสนอราคา '.number_format($quotation->total,2).' บาท</p>';
                        $html .= '<span class="noti-date">'.date('d/m/Y H:i:s',strtotime($quotation->created_at)).'</span>';
                    $html .= '</div>';
                $html .= '</a>';
            }else{
                $html .= '<a href="javascript:void(0)" data-noti="'.$noti->id.'" class="dropdown-noti clickread">';
                    $html .= '<div class="preview-thumbnail noti">';
                        $html .= '<img class="img-noti" src="'.url('image/ic_noti_message.png').'">';
                    $html .= '</div>';
                    $html .= '<div class="preview-item-content noti">';
                        $html .= '<p class="small-text mb-2">อนุมัติใบเสนอราคาเลขที่ '.$quotation->quotation_number.'</p>';
                        $html .= '<p class="small-text mb-2">ยอดรวมใบเสนอราคา '.number_format($quotation->total,2).' บาท</p>';
                        $html .= '<span class="noti-date">'.date('d/m/Y H:i:s',strtotime($quotation->updated_at)).'</span>';
                    $html .= '</div>';
                $html .= '</a>';
            }

        }else{
            $order = Orders::where('id',$noti->item_id)->first();
            if($order->on_vat == 1)
            {
                $check = 'all';
            }else{
                $check = 'notall';
            }

            if($noti->items_status == 0)
            {
                $html .= '<a href="javascript:void(0)" data-noti="'.$noti->id.'" class="dropdown-noti clickread">';
                    $html .= '<div class="preview-thumbnail noti">';
                        $html .= '<img class="img-noti" src="'.url('image/ic_noti_message.png').'">';
                    $html .= '</div>';
                    $html .= '<div class="preview-item-content noti">';
                        $html .= '<p class="small-text mb-2">สร้างบิลหลักเลขที่ '.$order->order_number.'</p>';
                        $html .= '<p class="small-text mb-2">ยอดรวมสั่งซื้อ '.number_format($order->total,2).' บาท</p>';
                        $html .= '<span class="noti-date">'.date('d/m/Y H:i:s',strtotime($order->created_at)).'</span>';
                    $html .= '</div>';
                $html .= '</a>';
            }else{
                $html .= '<a href="javascript:void(0)" data-noti="'.$noti->id.'" class="dropdown-noti clickread">';
                    $html .= '<div class="preview-thumbnail noti">';
                        $html .= '<img class="img-noti" src="'.url('image/ic_noti_message.png').'">';
                    $html .= '</div>';
                    $html .= '<div class="preview-item-content noti">';
                        $html .= '<p class="small-text mb-2">อนุมัติบิลหลักเลขที่ '.$order->order_number.'</p>';
                        $html .= '<p class="small-text mb-2">ยอดรวมสั่งซื้อ '.number_format($order->total,2).' บาท</p>';
                        $html .= '<span class="noti-date">'.date('d/m/Y H:i:s',strtotime($order->updated_at)).'</span>';
                    $html .= '</div>';
                $html .= '</a>';
            }
        }
        $noti->noti_status = 1;
        $noti->save();
        $this->html = $html;
        $this->check = $check;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['dev-frontend-channel'];
        // return new PrivateChannel('channel-name');
    }

    public function broadcastAs()
    {
        //$my_event = 'localhost-frontend-event';
        // $my_event = 'production-frontend-event';

        // $my_event = 'dev-frontend-event';
        return 'dev-frontend-event';
        // return $my_event;
    }
}
