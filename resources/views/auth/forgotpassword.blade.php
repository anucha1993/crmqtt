@extends('auth.layouts')
@section('content')
<style>
    svg.svg-inline--fa {
        position: absolute;
        top: 10px;
        left: 15px;
    }
    .auth-wrapper .authentication-form .logo-centered {
        margin-bottom: 100px;
    }
    .title-main{
        margin-top: -50px;
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
    .form-control.errorinput{
        border: 1px solid #e72424;
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
                    <a href="{{url('login')}}"><h1 class="text-center title-main">เจริญมั่น คอนกรีต</h1></a>
                    <div class="logo-centered">
                        {{-- <a href="../index.html"><img src="../src/img/brand.svg" alt=""></a> --}}
                    </div>
                    <h6>ลืมรหัสผ่าน ?</h6>
                    {{-- <p>We will send you a link to reset password.</p> --}}
                    <form action="{{route('auth.updaepass')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" id="emailinput" placeholder="Your email address" value="" onchange="checkmail(this.value)" required>
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <div class="sign-btn text-center">
                            <button type="submit" id="submit" class="btn btn-login">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    checksubmit = true;
    function checkmail(email)
    {
        $.ajax({
            url: "{{route('auth.checkmail')}}",
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'post',
            data: {email : email},
            success: function(res) {
                console.log(res);
                if(res.status === false)
                {
                    Swal.fire({
                        icon: 'error',
                        title: res.msg,
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                    $('#emailinput').addClass('errorinput')
                    $('#submit').prop('disabled', true);
                }else{
                    $('#emailinput').removeClass('errorinput')
                    $('#submit').prop('disabled', false);
                }
                checksubmit = res.status
            }
        })
    }
</script>
@stop
