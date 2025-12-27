   @extends('layouts.authLayout')
   @section('title', 'Log in')
   @section('content')
       <div class="login-box">
           <div class="login-logo">
            <a href="{{route('home')}}">{{config('app.name')}}</a>
           </div>
           <!-- /.login-logo -->
           <div class="card">
               <div class="card-body login-card-body">
                   <p class="login-box-msg">Sign in to start your session</p>

                   <form class="ajaxForm" action="{{ route('login') }}" method="post"
                       data-after-success-function-name="afterSuccess">
                       @csrf
                       <div class="mb-3">

                           <div class="input-group ">
                               <input type="email" class="form-control" name="email" placeholder="Email" />
                               <div class="input-group-append">
                                   <div class="input-group-text">
                                       <span class="fas fa-envelope"></span>
                                   </div>
                               </div>
                           </div>
                           <small class="text-danger email-error error-common"></small>
                       </div>

                       <div class="mb-3">
                           <div class="input-group ">
                               <input type="password" class="form-control" name="password" placeholder="Password" />
                               <div class="input-group-append">
                                   <div class="input-group-text">
                                       <span class="fas fa-lock"></span>
                                   </div>
                               </div>
                           </div>
                           <small class="text-danger password-error error-common"></small>
                       </div>
                       <div class="row">
                           <div class="col-12">
                               <div class="icheck-primary">
                                   <input type="checkbox" id="remember" name="remember" value="true" />
                                   <label for="remember"> Remember Me </label>
                               </div>
                           </div>
                           <!-- /.col -->
                           <div class="col-12">
                               <button type="submit" class="btn btn-primary w-100">
                                   Sign In
                               </button>
                           </div>
                           <!-- /.col -->
                       </div>
                   </form>
               </div>
               <!-- /.login-card-body -->
           </div>
       </div>
       <!-- /.login-box -->
   @endsection
   @push('bottom-ajax-utils-js')
       <script>
           function afterSuccess(res, e) {
               console.log(res);
               if (res.status) {
                   window.location.href = "{{ route('home') }}"
               }
           }
       </script>
   @endpush
