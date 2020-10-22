@extends('master-login')

@section('content')

<p>ENTER YOUR EMAIL</p>

<form action="{{ route('reset-password') }}" method="POST">
    
    {{ csrf_field() }}

    <div class="form-group form-group-default" id="emailGroup">
        <label>E-mail</label>
        <div class="controls">
            <input type="text" name="email" id="email" value="" placeholder="E-mail" class="form-control" required>
        </div>
    </div>
                    
    <button type="submit" class="btn btn-block login-button">
        <span class="signingin hidden"><span class="voyager-refresh"></span> SENDING RESET PASSWORD LINK...</span>
        <span class="signin">RESET PASSWORD</span>
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
    var email = document.querySelector('[name="email"]');
    btn.addEventListener('click', function(ev){
        if (form.checkValidity()) {
            btn.querySelector('.signingin').className = 'signingin';
            btn.querySelector('.signin').className = 'signin hidden';
        } else {
            ev.preventDefault();
        }
    });
    email.focus();
    document.getElementById('emailGroup').classList.add("focused");

    // Focus events for email fields
    email.addEventListener('focusin', function(e){
        document.getElementById('emailGroup').classList.add("focused");
    });
    email.addEventListener('focusout', function(e){
       document.getElementById('emailGroup').classList.remove("focused");
    });
</script>
@endsection