@extends('auth.layouts')
@section('content')
<style>
    svg.svg-inline--fa {
        position: absolute;
        top: 10px;
        left: 15px;
    }
    .auth-wrapper .authentication-form .logo-centered {
        margin-bottom: 55px;
    }
    .minititle{
        font-size: 20px;
    }
    .title-main{
        font-family: "Sarabun-bold", sans-serif;
        color: #33467A;
    }
    .btn:hover {
        color: #FFFF;
        text-decoration: none;
    }
    .auth-wrapper .lavalite-bg .lavalite-overlay {
        background: none;
    }
</style>
<div class="auth-wrapper">
    <div class="container-fluid h-100">
        <div class="row flex-row h-100 bg-white">
            <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
                <div class="lavalite-bg" style="background-image: url('/image/login-bg.png')">
                    <div class="lavalite-overlay"></div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
                <div class="authentication-form mx-auto">
                    <h1 class="text-center title-main">เจริญมั่น คอนกรีต</h1>
                    <div class="logo-centered">

                    </div>
                    <label class="text-center minititle">Welcome to เจริญมั่น คอนกรีต System</label>
                    <form action="{{route('users.singin')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email" required name="email" value="{{old('email')}}">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" required name="password" value="{{old('password')}}">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </div>
                        <div class="row">
                            <div class="col text-left">
                                {{-- <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="item_checkbox" name="item_checkbox" value="option1">
                                    <span class="custom-control-label">&nbsp;Remember Me</span>
                                </label> --}}
                            </div>
                            <div class="col text-right">
                                <a href="{{url('forgotpassword')}}">ลืมรหัสผ่าน ?</a>
                            </div>
                        </div>
                        <div class="sign-btn text-center" >
                            <button class="btn btn-login">เข้าสู่ระบบ</button>
                        </div>
                    </form>
                    <div class="register">
                        {{-- <p>Don't have an account? <a href="register.html">Create an account</a></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if(Session::has('message'))
        @if(Session::get('message') == 'loginfalse')
        <input type="hidden" name="message_login" value="{{ Session::get('message') }}">
        @elseif(Session::get('message') == 'message_login_status')
        <input type="hidden" name="message_login_status" value="{{ Session::get('message') }}">
        @elseif(Session::get('message') == 'message_chang_pass')
        <input type="hidden" name="message_chang_pass" value="{{ Session::get('message') }}">
        @endif
    @endif
    <script type="text/javascript">
    $(function(){

        var login = $('input[name="message_login"]').val();
        // console.log(login);
        if (login) {
            Swal.fire({
                icon: 'error',
                title: 'Login ไม่สำเร็จ',
                text: 'กรุณาเช็ค Username หรือ Password',
                showConfirmButton: true,
                confirmButtonText: 'ปิดหน้าต่าง',
            });
        }
        var loginstatus = $('input[name="message_login_status"]').val();
        // console.log(login);
        if (loginstatus) {
            Swal.fire({
                icon: 'error',
                title: 'Login ไม่สำเร็จ',
                text: 'Username นี้ถูกปิดการใช้งาน',
                showConfirmButton: true,
                confirmButtonText: 'ปิดหน้าต่าง',
            });
        }

        var message_chang_pass = $('input[name="message_chang_pass"]').val();
        if (message_chang_pass) {
            Swal.fire({
                icon: 'success',
                title: 'ลืมรหัสผ่านสำเร็จ',
                text: 'ระบบได้ส่งรหัสผ่านใหม่ไปในอีเมล์',
                showConfirmButton: true,
                confirmButtonText: 'ปิดหน้าต่าง',
            });
        }

    });
    </script>
@stop
