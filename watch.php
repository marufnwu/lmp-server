<?php
$link = isset($_GET['link']) ? $_GET['link'] : '';

if (empty($link)) {
    echo "Something went wrong";
    exit();
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lottery Master Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style1.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
</head>

<body>

    <div class="loader-container">
        <div class="loader"></div>
    </div>



    <?php
    $parts = parse_url($link);
    $host = $parts['host'];


    if ($host == 'www.youtube.com' || $host == 'youtube.com' || $host == 'youtu.be') {
        //youtube
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $match);
        $youtube_id = $match[1];

    ?>

        <section class="row">

            <div class="col">
                <iframe width='100%' height="315" src="https://www.youtube.com/embed/<?php echo $youtube_id ?>?autoplay=1&mute=0">
                </iframe>


            </div>

            <img class="img-fluid mt-8 col" src="pic2.jpg" alt="">


        </section>

    <?php

    } else if ($host == 'www.facebook.com' || $host == 'facebook.com' || $host== 'fb.watch') {
        //facebook
    ?>
        <section class="row">



            <div class="col">
                <div id="fb-root"></div>
                <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>

                <div class="fb-video" data-href="<?php echo $link ?>" data-allowfullscreen="true" data-width="auto" mute="0" data-autoplay="false"></div>


            </div>
            <img class="img-fluid mt-8 col" src="pic2.jpg" alt="">


        </section>


    <?php
    }
    ?>

    <script>
        $(window).on("load", function() {
            $(".loader-container").fadeOut(1000);
        });
    </script>



</body>

</html>