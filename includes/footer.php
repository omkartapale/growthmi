<?php
/**
 * Created by PhpStorm.
 * User: Omkar
 * Date: 14-05-2015
 * Time: 07:06 PM
 */
?>

<footer>
    <div class="row">
        <div class="col-sm-6">
            <div class="widget footer-info">

                <div class="widget-title">About Growth<sup>&#94;</sup></div>

                <div class="widget-text">
                    <p class="text-justify">
                        &#94; Symbol is called caret. It has several interpretations: in Latin &#94; means missing or
                        lacking, in writing &#94; is used to show where something is missing; whereas, in technology
                        &#94; is used for exponentiation - raising one quantity to the power of another. For us it is a
                        culmination of both - finding the missing link between our client’s problem statement and their
                        exponential growth story.
                    </p>

                </div>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="widget footer-links">
                <div class="widget-title">Discover</div>

                <div class="widget-text">
                    <ul>
                        <li><a href="services.php#end-to-end-services">End-to-End Services</a></li>
                        <li><a href="insights.php#opportunities">Identifying Growth Opportunities</a></li>
                        <li><a href="about.php#clients-grow">How do we help our clients grow</a></li>
                        <li><a href="contact.php">Contact us to know more</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="widget footer-links">
                <div class="widget-title">Services</div>

                <div class="widget-text">
                    <ul>
                        <li><a href="services.php#market-selection">Market Selection</a></li>
                        <li><a href="services.php#market-assessment">Market Assessment</a></li>
                        <li><a href="services.php#market-entry">Market Entry</a></li>
                        <li><a href="services.php#market-development">Market Development</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <p>&copy; 2017 Growth&#94; Market Intelligence. All rights reserved. Made in India with <i
                class="glyphicon glyphicon-heart"></i>
            from <a href="http://www.tech4geek.co.in" data-toggle="tooltip" title="Crafted at Tech4Geek Solutions."
                    target="_blank">Tech4Geek</a>
        </p>

        <p>
            GMI is registered as a private limited company in New Delhi, India. CIN - U74140DL2015PTC281543.
        </p>

        <p><a href="terms.php">Terms of use</a></p>
    </div>
</footer>
<?php

if (!$cookied):
    ?>
    <div class="navbar-fixed-bottom cookie-policy-prompt">
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p><!--We use cookies on this website. By using this site, you agree that we may store and access cookies on
                your device.-->
                We use cookies to improve your experience. By your continued use of this site you accept such use. To
                change your settings please see <a href="terms.php" class="" target="_blank">our terms of use policy</a>.
            </p>
        </div>
    </div>
<?php
endif;
?>
<div class="text-right">
        <span id="mail-link">
            <a href="mailto:asad@growthmi.com" target="_blank">
            <span class="fa-stack fa-2x">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-envelope fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </span>
        <span id="top-link-block" class="hidden">
        <a href="#top" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
            <span class="fa-stack fa-2x">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-chevron-up fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        </span><!-- /top-link-block -->
</div>

<!-- Modal Request Proposal -->
<div class="modal fade" id="request-proposal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" id="requestQuoteForm" data-toggle="validator" data-disable="false">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="requestModalLabel">Request a Proposal</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="requestType1" id="requestType"/>

                    <div id="response-proposal-error">

                    </div>

                    <div class="form-group has-feedback">
                        <label class="control-label" for="requestName">Name</label>
                        <input type="text" class="form-control" id="requestName" name="requestName"
                               placeholder="Enter full name" required="" data-toggle="tooltip" title="Your full name"/>
                        <span class="glyphicon glyphicon-flag form-control-feedback" aria-hidden="false"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="control-label" for="requestEmail">Email address</label>
                        <input type="email" class="form-control" id="requestEmail" name="requestEmail"
                               placeholder="Enter email" required="" data-toggle="tooltip"
                               title="Your email address for correspondence"/>
                        <span class="glyphicon glyphicon-user form-control-feedback" aria-hidden="false"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="control-label" for="requestPhone">Phone</label>
                        <input type="tel" class="form-control" id="requestPhone" name="requestPhone"
                               placeholder="Contact Number" required="" data-toggle="tooltip"
                               title="Your phone number"/>
                        <span class="glyphicon glyphicon-phone form-control-feedback" aria-hidden="false"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="control-label" for="requestComment">Comment</label>
                        <input type="text" class="form-control" id="requestComment" name="requestComment"
                               placeholder="Tell us more about your research requirements" required=""
                               data-toggle="tooltip" title="Your research requirement / Area of interest"/>
                        <span class="glyphicon glyphicon-comment form-control-feedback" aria-hidden="false"></span>
                    </div>
                    <div class="form-group">
                        <div id="g-recaptcha-modal"></div>
                        <span class="help-block">Please check that you are not a robot.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="requestType" id="requestType" value=""/>
                    <button type="submit" class="btn btn-primary" id="requestQuoteSubmit"
                            data-loading-text="Sending...">
                        <span class="glyphicon glyphicon-thumbs-up"></span>&nbsp; Let us help you grow!
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Response-->
<div class="modal fade" id="response-proposal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body  bg-primary">
                <p id="response" class="text-center"></p>

                <p class="text-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Okay</button>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src='https://www.google.com/recaptcha/api.js?onload=onloadRecaptchaCallback&render=explicit'
        type="text/javascript" defer></script>
<script src="vendor/intl-tel-input/js/intlTelInput.min.js" type="text/javascript"></script>
<script src="js/app.js" type="text/javascript"></script>
<?php if($header['active-page']=="contact"):?>
<script src="js/contact.js" type="text/javascript"></script>
<?php endif; ?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-91634005-1', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
