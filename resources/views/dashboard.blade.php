<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
  <body>
    <main>
        <div class="container py-4">
            <header class="pb-3 mb-4 border-bottom">                
                
            </header>
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid py-5">

                    @session('success')
                    <div class="alert alert-success" role="alert">
                        {{ $value }}    
                    @endsession
                    <h1 class="display-5 fw-bold">Welcome to the Dashboard</h1>
                    <h1 class="display-5 fw-bold">Hi, {{ auth()->user()->name }}</h1>
                    <p class="col-md-8 fs-4">This is a protected area. You can access this page only after logging in.</p>
                    <button class="btn btn-primary btn-lg" type="button">Learn more</button>
                </div>
            </div>
            <div class="text-right mb-3">
                    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  </body>
</html>