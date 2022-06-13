<?php
/**
 * Created by PhpStorm.
 * User: Omkar
 * Date: 14-05-2015
 * Time: 07:06 PM
 */

if(isset($_COOKIE['growthmi_visited'])) {
    $cookied = true;
}else{
    $cookied=false;
    setcookie('growthmi_visited',true);
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="<?php echo $header['meta-desc'] ?>"/>
    <meta name="keywords" content="<?php echo $header['meta-keywords'] ?>"/>

	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />

    <title><?php echo $header['title'] ?> - GMI</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="vendor/intl-tel-input/css/intlTelInput.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="top">

<header class="navbar-fixed-top">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <button type="button"
                        class="navbar-toggle collapsed"
                        data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- class="text-hide"-->
                <h1 class="text-hide">
                    <span class="growth">Growth</span><span class="growth-caret">&#94;</span>
                    <img src="img/logo.png"
                         alt="Growth Marketing Intelligence"/>
                </h1>

            </div>
            <div class="col-sm-8 hidden-xs text-right">
                <div class="top-links">
                    <a href="#request-proposal" data-toggle="modal" data-title="Quotation & Timeline" data-request-type="quoatation">Request Quotation &amp; Timeline</a>&nbsp;&nbsp;
                    <span>|</span>&nbsp;&nbsp;
                    <a href="#request-proposal" data-toggle="modal" data-title="Proposal" data-request-type="proposal">Request a Proposal</a>&nbsp;&nbsp;
                    <span>|</span>
                    &nbsp;&nbsp;
                    <a href="https://www.linkedin.com/company/growth-market-intelligence" target="_blank"><span
                            class="fa fa-linkedin"></span></a>
                    <a href="mailto:asad@growthmi.com" target="_blank">
                        <span class="fa-stack ">
                            <i class="fa fa-envelope fa-stack-1x "></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse navbar-collapse" id="navbar">
        <nav class="navbar navbar-default">
            <div class="container">
                <ul class="nav nav-justified">
                    <li class="<?php echo ($header['active-page']=='home')?'active':'';?>"><a href="/">Home</a></li>
                    <li class="<?php echo ($header['active-page']=='services')?'active':'';?>"><a href="services.php">Services</a></li>
                    <li class="<?php echo ($header['active-page']=='insights')?'active':'';?>"><a href="insights.php">Insights</a></li>
                    <li class="<?php echo ($header['active-page']=='about')?'active':'';?>"><a href="about.php">About</a></li>
                    <li class="<?php echo ($header['active-page']=='contact')?'active':'';?>"><a href="contact.php">Contact</a></li>
                    <li class="visible-xs"><a href="#request-proposal" data-toggle="modal" data-title="Quotation & Timeline" data-request-type="quoatation">Request Quotation &amp; Timeline</a></li>
                    <li class="visible-xs"><a href="#request-proposal" data-toggle="modal" data-title="Proposal" data-request-type="proposal">Request a Proposal</a></li>
                </ul>
            </div>
        </nav>
    </div>

</header>
