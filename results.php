<?php
    $shopurl = $_POST['shopurl'];
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <title>(e)food | fake reviews</title>
    <script>
        var shop = "<?php echo $shopurl; ?>";
        var jsonResponse;
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(shop);
                jsonResponse = JSON.parse(this.responseText);

                if(jsonResponse.result == true) {

                    document.getElementById("hr").innerHTML = "<hr>";

                    document.getElementById("shop-logo").src = jsonResponse.logo;

                    document.getElementById("shop-title").innerHTML = jsonResponse.title;

                    document.getElementById("fake-percent").innerHTML = "Ποσοστό ψεύτικων Reviews: " + jsonResponse.score + "%";

                    document.getElementById("stars").innerHTML = "Αστέρια στο efood: " + jsonResponse.stars + "/5";

                    document.getElementById("real-stars").innerHTML = "Πραγματικά αστέρια: " + jsonResponse.realStars + "/5";

                }else{
                    document.getElementById("stars").innerHTML = "Μη έγκυρο URL!";
                }
            }
        };
        xhttp.open("POST", "calculate.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("shopurl="+shop);

    </script>
</head>
<body>
<div class="s013">
        <div class="transbox">
            <div clas="back"><button class="btn" type="submit" onclick="window.location.href='index.php';">Πίσω</button></div>
            <div class="shop"><img id="shop-logo" src=""><span id="shop-title"></span></div>
            <div id="hr"></div>
            <p id="stars">Loading..</p>
            <p id="fake-percent"></p>
            <p id="real-stars"></p>
        </div>
</div>
</body>
</html>
