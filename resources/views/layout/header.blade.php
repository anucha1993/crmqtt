<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <h3>เจริญมั่น คอนกรีต</h3>
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
            <i class="mdi mdi-home"></i>
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
                <a class="dropdown-item py-3">
                    <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
                    <span class="badge badge-pill badge-primary float-right">View all</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                    <img src="{{ url('assets/images/faces/face10.jpg') }}" alt="image" class="img-sm profile-pic"> </div>
                    <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                    <p class="font-weight-light small-text"> The meeting is cancelled </p>
                    </div>
                </a>
                <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                    <img src="{{ url('assets/images/faces/face12.jpg') }}" alt="image" class="img-sm profile-pic"> </div>
                    <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                    <p class="font-weight-light small-text"> The meeting is cancelled </p>
                    </div>
                </a>
                <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                    <img src="{{ url('assets/images/faces/face3.jpg') }}" alt="image" class="img-sm profile-pic"> </div>
                    <div class="preview-item-content flex-grow py-2">
                    <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
                    <p class="font-weight-light small-text"> The meeting is cancelled </p>
                    </div>
                </a>
                </div>
            </li>
            {{-- noti --}}
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span data-count="{{CountNotReadNoti()}}" class="count noticount">{{CountNotReadNoti()}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list noti pb-0" aria-labelledby="notificationDropdown">
                    <div class="box-noti">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-inline p-t-20">
                                    <div class="col-sm-6">
                                        <span>การแจ้งเตือน</span>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="#" class="btn btn-link">ดูทั้งหมด</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-append-noti">
                            <div class="col-sm-12" id="append-noti">
                                {!! listNoti()!!}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        {{-- user --}}
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <span class="profile-text d-none d-md-inline-flex">{{Auth::user()->name}}</span>
                <img class="img-xs rounded-circle" src="{{ url('image/user.png') }}" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                {{-- <a class="dropdown-item p-0">
                    <div class="d-flex border-bottom w-100 justify-content-center">
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                    </div>
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                        <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                    </div>
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                    </div>
                    </div>
                </a>
                <a class="dropdown-item mt-2"> Manage Accounts </a>
                <a class="dropdown-item"> Change Password </a>
                <a class="dropdown-item"> Check Inbox </a> --}}
                <a href="{{url('changpass')}}" class="dropdown-item"> เปลี่ยนรหัสผ่าน </a>
                <a href="{{url('logout')}}" class="dropdown-item"> ออกจากระบบ </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="mdi mdi-menu icon-menu"></span>
        </button>
    </div>
    <form id="updatenoti" style="display: none">
    </form>
</nav>
@push('custom-scripts')
    <script>
        var userrole = '{{\Auth::user()->role_id}}';
        var notificationsCount     = parseInt($('.noticount').attr('data-count'));
        Pusher.logToConsole = true;
        var pusher_key = '6f1cc760afb032dc6489';
        var pusher = new Pusher(pusher_key, {
            cluster: 'ap1',
            forceTLS: true,
        });
        var channel = pusher.subscribe('dev-frontend-channel');
        channel.bind('dev-frontend-event', function(data) {
            if(data.check == 'all')
            {
                notificationsCount += 1;
                $('#append-noti').prepend(data.html);
                $('.noticount').html(notificationsCount);
                $('.noticount').attr('data-count',notificationsCount);
            }else{
                if(userrole != 2)
                {
                    notificationsCount += 1;
                    $('#append-noti').prepend(data.html);
                    $('.noticount').html(notificationsCount);
                    $('.noticount').attr('data-count',notificationsCount);
                }
            }
        });
        $('.clickread').on('click',function()
        {
            notiid = $(this).data('noti')
            var myForm = document.getElementById('updatenoti');
            var formData = new FormData(myForm);
            var csrf = $('meta[name="_token"]').attr('content');
            formData.append('_token', csrf);
            formData.append('notiid', notiid);

            $.ajax({
                type: "post",
                url: "{{route('noti.updatenoti')}}",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res)
                {
                    window.location = res.url
                }
            });
        })
    </script>
@endpush
