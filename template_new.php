<?php
session_start();
include 'includes/nav.php';
?>	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
</head>



<!-- START OF BODY TAG-->

<body>
    <div class="container border rounded my-3">
        <div class="row">
            <div class="col">
            <div class="row">
                    <div class="col">
                        <h1><u>Welcome!</u></h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sagittis, justo in hendrerit convallis, metus nunc interdum urna, vitae suscipit nulla lorem in elit. Morbi nunc eros, efficitur ut erat et, interdum interdum elit. Donec laoreet, magna sed facilisis bibendum, sem velit vulputate tellus, eget tincidunt neque lorem sed metus. Suspendisse potenti. Etiam neque est, tincidunt ac lorem ac, gravida facilisis urna. Vivamus placerat auctor augue, ac volutpat nisi placerat eget. Pellentesque eget ex est. Nullam sem risus, facilisis et ex a, tristique tempor lorem. Pellentesque lobortis sollicitudin nunc, vitae pellentesque nisl. Fusce blandit justo nibh, at facilisis sem placerat et. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed vel molestie nisi, vel pharetra neque. Sed congue aliquam turpis, nec lobortis dui sodales et. </p>
                    </div>
                    <div class="col">
                        <img class="img-fluid my-2" src="images\forest.jpg"></img>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- END OF BODY TAG-->

</html>

<?php
include 'includes/footer.php';
?>