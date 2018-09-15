
<script>var seconds_remaining = "10000"</script>
<!DOCTYPE html>
<html lang="en">
<head>




    <meta charset="utf-8">
    <!-- <title></title> -->
    <title>Next Step Test Preparation, LLC - Full Length 1</title>
    <link rel="stylesheet" type="text/css" href="https://mcat.nextsteptestprep.com/studenttheme/css/testview.css?v=3.0">

    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rangy/1.3.0/rangy-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rangy/1.3.0/rangy-classapplier.min.js"></script>


    <script src="https://mcat.nextsteptestprep.com/coursetheme/js/sorttable.js"></script>



    <!-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/> -->



    <style>

        *, *:before, *:after
        {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        html, body
        {
            width: 100%;
            height: 100%;

            margin: 0;
            padding: 0;
        }


        .flex-container-vert {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            height: 100%;
        }



        .flexbox-parent
        {
            width: 100%;
            height: 100%;

            display: flex;
            flex-direction: column;

            justify-content: flex-start; /* align items in Main Axis */
            align-items: stretch; /* align items in Cross Axis */
            align-content: stretch; /* Extra space in Cross Axis */

            /* background: rgba(255, 255, 255, .1); */
        }

        .flexbox-item
        {
            /* padding: 8px; */
        }
        .flexbox-item-grow
        {
            flex: 1; /* same as flex: 1 1 auto; */
        }

        .flexbox-item.header
        {
            /* background: rgba(255, 0, 0, .1); */
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
        }
        .flexbox-item.footer
        {
            background: rgba(0, 255, 0, .1);
        }
        .flexbox-item.content
        {
            background: rgba(0, 0, 255, .1);
        }

        .fill-area
        {
            overflow-y: auto;  /* important for firefox overflow */
        }

        .fill-area
        {
            display: flex;
            flex-direction: row;

            justify-content: flex-start; /* align items in Main Axis */
            align-items: stretch; /* align items in Cross Axis */
            align-content: stretch; /* Extra space in Cross Axis */

        }
        .fill-area-content
        {
            /* background: rgba(0, 0, 0, .3); */
            /* border: 1px solid #000000; */

            /* Needed for when the area gets squished too far and there is content that can't be displayed */
            overflow: auto;
        }

    </style>


</head>

<!-- <body onresize="resizeDisplayLayer()"  oncontextmenu="return false"> -->
<!-- <body oncontextmenu="return false"> -->


<div class="flexbox-parent" id="container">
    <div class="flexbox-item header">
        <!-- Header -->




        <!-- Info Layers -->
        <div id = "test_infoPanel">
            <div id = "test_infoPanel_layer1">
                <div id = "test_infoPanel_layer1_segment1">
                    {{$exam->examTitle}} - {{$examinee}}				</div>
                <div id = "test_infoPanel_layer1_segment2">
                    <div id = "timerDiv">
						<span id = "timerDivContent" class = "yellowHover" style="color:#fff">
							<img id = "timerDivContent_img" src = "https://mcat.nextsteptestprep.com/images/testing/timer_white.png" style = "margin-right:4px;">
													</span>
                    </div>	<!-- timerDiv-->
                    <div id = "questionNumberDiv">
						<span class = "yellowHover" id = "timerSpan"   style="color:#fff">
							<img  id = "timerSpan_img" src = "https://mcat.nextsteptestprep.com/images/testing/questionnumber_white.png"  style = "margin-right:2px;margin-top:1px;">
							<div id="questionNumber">
                            </div>
						</span  class = "yellowHover">
                    </div>
                </div>
            </div>

            <div id = "test_infoPanel_layer2"> <!-- Highlight, Strike, Flag -->


            </div> <!-- test_infoPanel_layer2 -->

            <div id = "test_infoPanel_layer3">
                <div style = "width:33.3%;float:left;">
                    <div id = "ns_copyright">(c) Next Step Test Preparation, LLC</div>
                </div>
                <div style = "width:33.3%;float:left;">
                    <button id = "pauseButton" onclick="pausetimer()">Pause</button>				</div>
                <div style = "width:33.3%;float:left;">
                    &nbsp;
                </div>

            </div>
        </div>
























    </div>



    <style>
        .instructionsShell {
            margin: 0 20px;
        }

        .centered {
            text-align: center;
        }

        .ui-accordion-header,
        .ui-accordion-header-collapsed {
            background-color: #7faee0;
            background: #7faee0;
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 0;
        }

        .ui-accordion-header-active {
            background-color: #006daa;
            background: #006daa;
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 0;
        }

        .ui-accordion-header-icon {
            background-color: #fff;
            border-radius: 25px;
            /* margin-top:1px; */
            height: 18px;
            width: 18px;
            border: 1px solid white;
            border-top: 0;
        }


    </style>



    <div class="flexbox-item fill-area content flexbox-item-grow" style = "background-color:#ffffff;">
        <div class="fill-area-content flexbox-item-grow">


            <div class = 'instructionsShell'><center><br><img src = '{{asset('assets/img/logo.png')}}'></center><br><h1>Full Length 1</h1><br>
                <p>Welcome to Next Step Test Preparation's Full-Length Practice MCAT Exam. This practice exam simulates the format of the current MCAT exam, including the MCAT testing interface (revised in 2018).</p>
                <p>While you may wish to work some exams section-by-section, plan to take at least 3 exams in a test-like environment (over the course of a single day with only approved 10 and 30-minute breaks). </p>
                <p>Please note that you cannot open multiple versions of the same test at the same time (for example in different browser windows). The system will let you attempt each test five times, but to avoid any errors in the system, please do not have two tests open at the same time.</p>
                <p>This practice exam is a simulation. It has not been reviewed, endorsed, or approved by the AAMC. MCAT is a trademark of the AAMC.</p>
            </div>


        </div> <!-- <div class="fill-area-content flexbox-item-grow"> -->
    </div> <!-- <div class="flexbox-item fill-area content flexbox-item-grow"> -->

    <style>
        #test_controlBar_leftX {
            width:30%;
        }
        #test_controlBar_rightX {
            width:70%;
        }
    </style>


    <!-- Control Bar Layers -->
    <div id = "test_controlBar"  class="flexbox-item footer">
        <div id = "test_controlBar_left"  style = "float:left;">
            &nbsp;



        </div>


        <div id = "test_controlBar_right"  style = "float:right;">


            <div id = "nextButton" class = "controlBar_buttons_right yellowHover" onclick = "window.location = 'https://mcat.nextsteptestprep.com/mcattest/launch/37/i/2'"><div style = "float:left"><u>N</u>ext&nbsp;</div><img id = "nextButton_img" src = "https://mcat.nextsteptestprep.com/images/testing/rightarrow_white.png" style = "margin-top:1px;margin-left:0px;margin-right:5px;"></div>





        </div> <!-- test_controlBar_right -->
    </div> <!-- <div id = "test_controlBar"  class="flexbox-item footer">-->
</div> <!--container-->




<script>

    // resizeDisplayLayer();


    // $(document).ready(function() {
    // 	resizeDisplayLayer();
    // });

    // function resizeDisplayLayer()
    // {

    // 	var h = $(window).height() - 111 - 45; //111 top, 45 bottom
    // 	$("#test_displayPanel").height(h);
    // 	// $("#test_infoPanel_main").height(h);
    // 	// $("#test_infoPanel_main_left").height(h-10); //10 padding
    // 	// $("#test_infoPanel_main_right").height(h);


    // 	// // Left and Right Div - 2 Column Layout
    // 	// var w = (($(window).width() - 80)/2)-10; //40px margins on both sides; another 10 for blue divider
    // 	// $("#test_infoPanel_main_left").width(w);
    // 	// $("#test_infoPanel_main_right").width(w);

    // 	// console.log($(window).width());
    // 	// console.log(w);


    // }

</script>


















<script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.1/mousetrap.js"></script>

<script>
    Mousetrap.bind('alt+n', function(e) {
        $('#nextButton').click();
    });
</script>
</body></html>

<script>
    //Save/Update Continue URL

    var attemptnumber = "3";
    var testid = "37";
    var area = "i";
    var areaid = "1";
    var qgo = "";
    var secondsremaining_initial = "10000";
    var questionNumber = "";
    var sessionTestMode = "";
    var attemptid = "536174";

    //If any of the above variables are null - we should save x instead, since null will be ignorerd in the continue url structure
    if(qgo == "")
        qgo = "x";
    if(secondsremaining_initial == "")
        secondsremaining_initial = "x";
    if(seconds_remaining == "")
        seconds_remaining = "x";
    if(questionNumber == "")
        questionNumber = "x";
    if(sessionTestMode == "")
        sessionTestMode = "x";


    console.log("Attempt Number: "+ attemptnumber);
    console.log("Test Id: "+ testid);
    console.log("Area: " +area);
    console.log("Area ID: " +areaid);
    console.log("QGO: " +qgo);
    console.log("Seconds Remaining Initial: " +secondsremaining_initial);
    console.log("Seconds Remaining: " +seconds_remaining);
    console.log("Q#: " +questionNumber);
    console.log("Session Test Mode: " +sessionTestMode);
    console.log("Attempt Id: " +attemptid);






    $( document ).ready(function() {
        console.log( "Ready!" );
        saveContinueURL() // SAVE AT PAGE LOAD;
    });


    //60 Second Timer - Save
    var saveTimer = setInterval(function(){runSaveTimer()},60000); //runs every 60 seconds


    function runSaveTimer()
    {
        // console.log("Attempt Number: "+ attemptnumber);
        // console.log("Test Id: "+ testid);
        // console.log("Area: " +area);
        // console.log("Area ID: " +areaid);
        // console.log("QGO: " +qgo);
        // console.log("Seconds Remaining Initial: " +secondsremaining_initial);
        // console.log("Seconds Remaining: " +seconds_remaining);
        // console.log("Q#: " +questionNumber);
        // console.log("Session Test Mode: " +sessionTestMode);
        // console.log("Attempt Id: " +attemptid);

        saveContinueURL();

    }

    function saveContinueURL()
    {
        console.log("Save Curl");

        //Save Data - AJAX
        $.post("https://mcat.nextsteptestprep.com/" + "mcattest/save_continueTestURL_AJAX",
                {
                    attemptnumber: attemptnumber,
                    testid: testid,
                    area: area,
                    areaid: areaid,
                    qgo: qgo,
                    secondsremaining: seconds_remaining,
                    questionNumber: questionNumber,
                    sessionTestMode: sessionTestMode,
                    attemptid: attemptid,
                },
                function(data, status){
                    // console.log("Data: " + data + "\nStatus: " + status);
                });
    }


</script>

