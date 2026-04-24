<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size: cover;
    background-position: center;
}

*{
    box-sizing: border-box;
}

/* NAVBAR */
.navbar {
    position: absolute;
    top: 0;
    width: 100%;
    padding: 20px 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.navbar h2 {
    font-weight: 600;
    letter-spacing: 1px;
}

.navbar a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
    font-weight: 300;
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
    padding: 30px;
}

.left h3 {
    font-weight: 300;
    line-height: 1.4;
}

/* RIGHT FORM */
.right {
    width: 60%;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.right h2 {
    margin-bottom: 25px;
    font-weight: 600;
}

/* FORM */
input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

input:focus {
    border-color: #8B5E3C;
    outline: none;
}

button {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 5px;
    margin-top: 20px;
    background: #8B5E3C;
    color: white;
    border: 1px solid transparent;
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
}

button:hover {
    background: #6f4a2d;
}

.toggle {
    text-align: center;
    margin-top: 1px;
    font-size: 13px;
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
            <h3>"Every sip has its own story"</h3>
        </div>

        <!-- RIGHT -->
        <div class="right">

            <!-- LOGIN -->
            <div id="loginForm">
                <h2>Login</h2>

                <form method="POST" action="/login">
                    @csrf

                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>

                    <button type="submit">Login</button>
                </form>

                <div class="toggle" onclick="showRegister()">
                    Don't have an account? Sign up
                </div>
            </div>

            <!-- REGISTER -->
            <div id="registerForm" class="hidden">
                <h2>Register</h2>

                <form method="POST" action="/register">
                    @csrf

                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>

                    <button type="submit">Sign Up</button>
                </form>

                <div class="toggle" onclick="showLogin()">
                    Already have an account? Login
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