<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Mother care tool</title>
    <link rel="stylesheet" type="text/css" href="{{ url() }}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ url() }}/assets/css/font-awesome.min.css" />
    <script type="text/javascript" src="{{ url() }}/assets/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="{{ url() }}/assets/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <div class="navbar navbar-default">
        <a class="navbar-brand" href="{{ url() }}"><span class="glyphicon glyphicon-home"> Home</span></a>
    </div>


<div class="page-header">
    <h1 style=" color: #AB47BC " > FAQ <small style=" color: #AB47BC ">Frequently Asked Questions</small></h1>
</div>

<!-- Bootstrap FAQ - START -->
<div class="container">
    <br />
    

    <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
       For any other questions, write to <a href="//www.help@sevasetu.org">help@sevasetu.org</a>.
    </div>

    <div class="panel-group" id="accordion">
        <div class="faqHeader">General questions</div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">- What is the Each One Reach One <strong>(EORO)</strong>  program?</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                   <strong>The Each One Reach One</strong> program is Seva Setu's flagship program to ensure that expecting and lactating mothers in rural India are guided and informed at all stages of their motherhood. This is done by pairing them up with women from urban India. These urban women get to build a personal relationship with the rural mothers and ensure they're on the path to a safe and healthy motherhood.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">- Who are <strong>Call Champions</strong>?</a>
                </h4>
            </div>
            <div id="collapseTen" class="panel-collapse collapse">
                <div class="panel-body">
                   We proudly call our urban women who get on board this program as <strong>Call Champions</strong>! They're champions of the cause to provide best of benefits to rural mothers.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">- Great! Why do I need to register on this web application then?</a>
                </h4>
            </div>
            <div id="collapseEleven" class="panel-collapse collapse">
                <div class="panel-body">
                     This web application allows you to assign to yourself rural mothers who are a part of this program. You will be shown a dashboard through which you can completely manage your weekly/monthly phone call schedules with them.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">- Ok. So if I'm a Call Champion, I just have to make phone calls? Is that it? Will there be no field visits?</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    The beauty of this program is that it ensures personalized guidance and monitoring of rural mothers through minimal supervision from their urban peers. Your primary medium of ensuring that all's well with your rural peer would be through phone calls. You can use this application to make meticulous notes of what you observe from each call. You could escalate issues through this application in case you think there is need for a manual intervention - our field volunteers would then go into action mode. Having said this, you are more than welcome to visit these mothers on the field anytime you like - we'll be happy to help coordinate these visits!
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">- Sounds good. How many phone calls a week will I be expected to make? </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                     It's totally up to you. Once you login to this application, you will be able to choose how many mothers you want to guide. If it's just one mother you've decided to talk to, expect to make one phone call in two weeks. Typically, Call Champions guide 5-6 of their rural peers, resulting in 1-2 calls each week. More, the merrier.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThreei">- Are these calls following some fixed schedule? Is it strictly one call in two weeks? </a>
                </h4>
            </div>
            <div id="collapseThreei" class="panel-collapse collapse">
                <div class="panel-body">
                     Good question. Yes, these calls follow a particular schedule. We gather from the field the expected date of delivery of a mother. Based on this date, we calculate 10 interventions spread over a course of 50 weeks - roughly half of them which intervene pre-pregnancy and half which intervene post-pregnancy. These interventions also have specific follow-up items which we expect the Call Champions to bring up on their calls. This is just the minimum number of interventions we recommend. Again, more the merrier as long it's convenient to your rural peer.<br/>
                     Take a look at the interventions we recommend <a href="{{ url() }}/admin/FAQ/checklist" target="_blank">here </a>.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">- All this sounds good. I just register on this application and I start off?</a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse">
                <div class="panel-body">
                     Yes and no. We'll be notified once you register. We'll give you a phone call and talk to you about the application and walk you through its usage. Once you're comfortable using this application, we will then ensure a previous Call Champions speaks to you and highlights some key learnings from her experience. Post this, you'll shadow one existing Call Champion in her weekly call. After that, the stage is all yours.
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">- What specific features does this application have?</a>
                </h4>
            </div>
            <div id="collapseSix" class="panel-collapse collapse">
                <div class="panel-body">
                    This application will primarily act as your calendar. It will ensure you're kept upto date about all the calls you ought to be making. Specifically, it will:
                    <ul>
                        <li>allow you to assign as many rural peers as you can manage.</li>
                        <li>help you re-schedule any existing scheduled phone calls.</li>
                        <li>automatically notify you over SMS and email on the calls you have in a week.</li>
                        <li>allow you to escalate specific issues you escalate to our field executives and track whether they've followed up on it.</li>
                        <li>go through the case history of a rural peer you have been assigned to in case she was being guided by another call champion till then.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEight">- This is so cool!</a>
                </h4>
            </div>
            <div id="collapseEight" class="panel-collapse collapse">
                <div class="panel-body">
                    Yes! It absolutely is! Allow this application to help you help society!<br/>
                    Here is a <a href="//embeds.audioboom.com/boos/2441188-mother-care-calls/embed/v4?eid=AQAAAJSVnVfkPyUA">link</a> which has a previous call recorded. This will give you an idea of what this program will expose you to.
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTeni">- Anything else?</a>
                </h4>
            </div>
            <div id="collapseTeni" class="panel-collapse collapse">
                <div class="panel-body">
                    At the end of the day, this program has been designed to ensure that your rural peers have a friend who is experienced and knowledgeable in these affairs. Please ensure that you build a relationship as a peer and not as a supervisor who is playing the part of a 'knowledge provider'. Else, we think this is a fantastic step forward you've decided upon. All the very best!
                </div>
            </div>
        </div>
        <br/>

    </div>
</div>

<style>
    .faqHeader {
        font-size: 27px;
        margin: 20px;
    }

    .panel-heading [data-toggle="collapse"]:after {
        font-family: 'Glyphicons Halflings';
        content: "\e072"; /* "play" icon */
        float: right;
        color: #F58723;
        font-size: 18px;
        line-height: 22px;
        /* rotate "play" icon from > (right arrow) to down arrow */
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
    }

    .panel-heading [data-toggle="collapse"].collapsed:after {
        /* rotate "play" icon from > (right arrow) to ^ (up arrow) */
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        transform: rotate(90deg);
        color: #454444;
    }
</style>

<!-- Bootstrap FAQ - END -->

</div>

</body>
</html>