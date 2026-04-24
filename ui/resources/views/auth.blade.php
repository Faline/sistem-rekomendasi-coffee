<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size: cover;
    background-position: center;
}

/* NAVBAR */
.navbar {
    position: absolute;
    top: 0;
    width: 100%;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.navbar h2 {
    margin: 0;
}

.navbar a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
}

/* CENTER */
.container {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.card {
    width: 800px;
    height: 450px;
    display: flex;
    border-radius: 20px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.9);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

/* LEFT IMAGE */
.left {
    width: 40%;
    background: linear-gradient(
        rgba(0,0,0,0.4),
        rgba(0,0,0,0.4)
    ), 
    url('https://images.unsplash.com/photo-1511920170033-f8396924c348') no-repeat center;
    background-size: cover;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
}

/* RIGHT FORM */
.right {
    width: 60%;
    padding: 40px;
}

/* FORM */
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 10px;
    background: #8B5E3C;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

button:hover {
    background: #6f4a2d;
}

.toggle {
    text-align: center;
    margin-top: 10px;
    color: #8B5E3C;
    cursor: pointer;
}

.hidden {
    display: none;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h2>Cangkir Kita</h2>
    <div>
        <a href="#">Home</a>
        <a href="#">Menu</a>
        <a href="#">About</a>
    </div>
</div>

<div class="container">
    <div class="card">

        <!-- LEFT -->
        <div class="left">
            <h3>"Setiap tegukan punya ceritanya sendiri</h3>
        </div>

        <!-- RIGHT -->
        <div class="right">

            <!-- LOGIN -->
            <div id="loginForm">
                <h2>Login</h2>

                <input type="text" placeholder="Email">
                <input type="password" placeholder="Password">

                <button>Login</button>

                <div class="toggle" onclick="showRegister()">
                    Belum punya akun? Daftar
                </div>
            </div>

            <!-- REGISTER -->
            <div id="registerForm" class="hidden">
                <h2>Register</h2>

                <input type="text" placeholder="Nama">
                <input type="email" placeholder="Email">
                <input type="password" placeholder="Password">

                <button>Daftar</button>

                <div class="toggle" onclick="showLogin()">
                    Sudah punya akun? Login
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function showRegister() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('registerForm').classList.remove('hidden');
}

function showLogin() {
    document.getElementById('registerForm').classList.add('hidden');
    document.getElementById('loginForm').classList.remove('hidden');
}
</script>

</body>
</html>