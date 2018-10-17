<?php
    $shopurl = $_POST['shopurl'];
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href="css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <title>(e)food | fake reviews</title>
    <script>var shop = "<?php echo $shopurl; ?>";</script>
</head>
<body>

<div class="loader__box">
    <img src="images/gif.gif" class="loader">
</div>


<div class="card">
    <div class="card__box">

        <div class="card__header">
          <img src="" alt="Logo" class="card__image">
          <h1 class="card__title"></h1>
        </div>
        
        <div class="card__body">
          <div class="fake__reviews">
            <h2 class="fake__reviews__title">Ποσοστό ψεύτικων <span>Reviews : </span></h2>
            <div id="container"></div>
          </div>
          
          <!-- EFOOD STARS -->
          <div class="stars">
            <h2 class="stars__title">Αστέρια στο efood : </h2>
            <div class="stars__outer">
                <div class="stars__inner"></div>
            </div>
            <span class="stars__rating"></span>
          </div>

          <!-- REAL STARS -->
          <div class="stars">
              <h2 class="stars__title">Πραγματικά αστέρια : </h2>
              <div class="stars__outer">
                  <div class="stars__inner"></div>
              </div>
              <span class="stars__rating"></span>
          </div>
          <!-- BACK BUTTON -->

        </div>
    </div>
</div>

<script src="progressbar.min.js"></script>
<script src="app.js"></script>
</body>
</html>