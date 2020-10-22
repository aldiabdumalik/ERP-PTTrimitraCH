@extends('master-login')

@section('content')

<p>SIGN IN BELOW</p>

    <form action="{{ url('login') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group form-group-default" id="nikGroup">
            <label>NIK</label>
            <div class="controls">
                <input type="text" name="userid" id="nik" value="" placeholder="NIK" class="form-control" required>
            </div>
        </div>

        <div class="form-group form-group-default" id="passwordGroup">
            <label>Password</label>
            <div class="controls">
                <input type="password" name="password" placeholder="password" class="form-control" required>
            </div>
        </div>
                        
        <div class="form-group" id="rememberMeGroup">
            <div class="controls">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- <input type="checkbox" name="remember" id="remember" value="1"><label for="remember" class="remember-me-text">Remember Me</label> -->
                    </div>
                    <div class="col-sm-6" style="text-align:right">
                        <a href="{{ route('reset-password') }}">Forgot Password?</a>
                    </div>
                </div>
            </div>                    
        </div>
                        
        <button type="submit" class="btn btn-block login-button">
            <span class="signingin hidden"><span class="voyager-refresh"></span> LOGGING IN...</span>
            <span class="signin">LOGIN</span>
        </button>

    </form>

              <div style="clear:both"></div>


              @if(!$errors->isEmpty())
              <div class="alert alert-red">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
              </div>
              @endif

@endsection

@section('js')
<script>
    var btn = document.querySelector('button[type="submit"]');
    var form = document.forms[0];
    var email = document.querySelector('[name="userid"]');
    var password = document.querySelector('[name="password"]');
    btn.addEventListener('click', function(ev){
        if (form.checkValidity()) {
            btn.querySelector('.signingin').className = 'signingin';
            btn.querySelector('.signin').className = 'signin hidden';
        } else {
            ev.preventDefault();
        }
    });
    email.focus();
    document.getElementById('nikGroup').classList.add("focused");

    // Focus events for nik and password fields
    email.addEventListener('focusin', function(e){
        document.getElementById('nikGroup').classList.add("focused");
    });
    email.addEventListener('focusout', function(e){
       document.getElementById('nikGroup').classList.remove("focused");
    });

    password.addEventListener('focusin', function(e){
        document.getElementById('passwordGroup').classList.add("focused");
    });
    password.addEventListener('focusout', function(e){
       document.getElementById('passwordGroup').classList.remove("focused");
    });

</script>
@endsection