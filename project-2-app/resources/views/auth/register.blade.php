<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<div class="container-fluid  ">
    <div class="row d-flex justify-content-center align-items">
        <main class="col-sm-8 col-lg-5 m-5   border rounded shadow">
            <div class="pt-5 mb-5 mt-4 text-center">
                <h2 class="fw-bold fs-3  secondary-emphasis">ساخت حساب کاربری</h2>
            </div>
            <div>
{{--                @if($errors->any())--}}
{{--                    @foreach($errors->all as $error) --}}
{{--                        --}}
{{--                    @endforeach--}}
{{--                @endif--}}
                <form action="{{route('api.register')}}" method="POST" >
                    @csrf
                    <div class="mx-5 my-3">
                        <label class="form-label ">نام</label>
                        <input  class="p-2 form-control form-control-lg" type="text" name="name">
{{--                        <div class="form-text text-danger">@error('name') {{$message}} @enderror</div>--}}
                    </div>
                    <div class="mx-5 my-3">
                        <label class="form-label ">ایمیل</label>
                        <input  class="p-2 form-control form-control-lg" type="text" name="email">
{{--                        <div class="form-text text-danger">@error('email') {{$message}} @enderror</div>--}}
                    </div>
                    <div class="mx-5 my-3">
                        <label class="form-label">رمز عبور</label>
                        <input class="p-2 form-control form-control-lg" type="password" name="password">
{{--                        <div class="form-text text-danger">@error('password') {{$message}} @enderror</div>--}}
                    </div>
                    <div class="mx-5 my-3">
                        <label class="form-label"> تکرار رمز عبور</label>
                        <input class="p-2 form-control form-control-lg" type="password" name="password_confirmation">
{{--                        <div class="form-text text-danger">@error('password_confirmation') {{$message}} @enderror</div>--}}
                    </div>
                    <div class="my-5 mx-5">
                        <select class="form-select form-select-lg "  aria-label=".form-select-lg example " name="role">
                            <option  value="admin">مدیر</option>
                            <option  value="user">کاربر</option>
                        </select>
                    </div>
                    <div class="mx-5 ">
                        <button type="submit" class="col-12 btn btn-primary p-3 mt-4" name="submit">ایجاد</button>
                    </div>
                </form>
                <div class="text-center mt-4 mb-5">
                    <p class="">قبلا ثبت نام کرده اید؟ <a href="login.php" class="text-decoration-none">ورود به حساب</a></p>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>



