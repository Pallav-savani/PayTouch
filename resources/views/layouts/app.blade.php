<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PayTouch - Web App</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
            <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

</head>
<body>
    <nav class="one navbar navbar-expand-lg sticky-top justify-content-center" aria-label="Offcanvas navbar large">
        <!-- <div class="d-flex"> -->
        <div class="m-0 p-0 logo-main mx-auto">
            <div class="d-flex justify-content-center logo countdown">
                <a class="text-decoration-none" href="index.php">
                    <img height="65px" src="{{ asset('images/logo.png') }}" />
                </a>
            </div>
            <a class="text-align-center m-0 text-decoration-none" href="#"><span class="spcolor">PayTouch
                </span><span class="spcolor2">Web Solution </span></a>
        </div>
        <div class="position-absolute end-0 me-3">
            <a href="#" onclick="logout()" class="text-decoration-none">
                <button type="button" class="btn btn-outline-danger px-3">
                    <i class="fa-solid fa-power-off fa-xl"></i>
                </button>
            </a>
        </div>
    </nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function logout() {
        const token = localStorage.getItem("auth_token");

        $.ajax({
            url: 'http://127.0.0.1:8000/api/logout',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(res) {
                alert(res.message);
                // Optionally redirect to login page
                window.location.href = '/';
            },
            error: function(xhr) {
                alert('Logout failed');
                console.error(xhr.responseText);
            }
        });
    }
</script>

</body>
</html>