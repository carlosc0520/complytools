<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <style>
      .container {
        background-color: #E6E6E6;
        display: flex;
        height: 400px;
        justify-content: center;
        align-items: center;
      }

      .card {
        margin: auto;
        background-color: white;
        border-radius: 20px;
        padding: 20px;
      }

      .title {
        font-weight: bold;
        font-size: 24px;
      }

      .btn {
        background-color: #81D742;
        font-weight: bold;
        width: 200px;
        height: 40px;
        border-radius: 20px;
        border: none;
        font-size: 16px;
      }

      .link {
        text-decoration: none;
        color: white !important;
      }
    </style>
  </head>

  <body>
    <div class="container">
      @yield('content')
    </div>
  </body>
</html>