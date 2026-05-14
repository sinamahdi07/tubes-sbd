<x-guest-layout>

<style>
/* Background halaman */
body{
    margin:0;
    padding:0;
    font-family:Arial, Helvetica, sans-serif;

    /* Background ala Steam */
    background:
        linear-gradient(rgba(10,15,25,0.85), rgba(10,15,25,0.9)),
        url('/images/The-Best-Survival-Games-For-Android (1).jpg');

    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;
}

/* Container login */
.login-box{
    width:420px;
    background:rgba(23,29,37,0.92);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:18px;
    padding:40px;
    backdrop-filter:blur(12px);
    box-shadow:0 0 40px rgba(0,0,0,0.6);
    color:white;
}

/* Judul */
.login-title{
    text-align:center;
    margin-bottom:35px;
}

.login-title h1{
    font-size:42px;
    margin-bottom:8px;
    letter-spacing:2px;
    color:#ffffff;
}

.login-title p{
    color:#8f98a0;
    font-size:14px;
}

/* Form */
.form-group{
    margin-bottom:22px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#66c0f4;
    font-size:13px;
    text-transform:uppercase;
    font-weight:bold;
}

.form-group input{
    width:100%;
    padding:14px;
    background:#32353c;
    border:1px solid #4c5664;
    border-radius:8px;
    color:white;
    outline:none;
    font-size:15px;
    transition:0.3s;
}

.form-group input:focus{
    border-color:#66c0f4;
    box-shadow:0 0 12px rgba(102,192,244,0.4);
}

/* Tombol login */
.login-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:linear-gradient(90deg,#06bfff,#2d73ff);
    color:white;
    font-weight:bold;
    font-size:18px;
    cursor:pointer;
    transition:0.3s;
}

.login-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 0 15px rgba(45,115,255,0.5);
}

/* Link register */
.register-link{
    margin-top:25px;
    text-align:center;
    color:#8f98a0;
    font-size:15px;
}

.register-link a{
    color:#66c0f4;
    text-decoration:none;
    font-weight:bold;
}

.register-link a:hover{
    text-decoration:underline;
}
</style>

<div class="login-box">

    <div class="login-title">
        <h1 class="text-2xl font-bold tracking-wide text-[#66c0f4]">
                        PlayMart
                    </h1>
        <p>Sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="login-btn">
            SIGN IN
        </button>

        <div class="register-link">
            Belum punya akun?
            <a href="{{ route('register') }}">
                Register
            </a>
        </div>

    </form>

</div>

</x-guest-layout>