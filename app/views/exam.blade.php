<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Next Step Test Preparation, LLC - Full Length 1</title>
    <link rel="stylesheet" type="text/css" href="https://mcat.nextsteptestprep.com/studenttheme/css/testview.css?v=3.0">

    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rangy/1.3.0/rangy-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rangy/1.3.0/rangy-classapplier.min.js"></script>

    <script src="https://mcat.nextsteptestprep.com/coursetheme/js/sorttable.js"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/onlineExamCss.css')}}"/>

</head>

<body>
    <div class="flexbox-parent" id="container">
        <div class="flexbox-item header">
            <!-- Header -->
            <!-- Info Layers -->
            <div id = "test_infoPanel">
                <div id = "test_infoPanel_layer1">
                    <div id = "test_infoPanel_layer1_segment1">{{$exam->examTitle}} - {{$examinee}}</div>
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
                            </span>
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

                @if($step == 1)
                <div class="flexbox-item fill-area content flexbox-item-grow" style = "background-color:#ffffff;">
                    <div class="fill-area-content flexbox-item-grow">
                        <div class = 'instructionsShell'><center><br><img src = '{{asset('assets/img/logo.png')}}'></center><br><h1>Exam Category -1</h1><br>
                            <p>Welcome to Next Step Test Preparation's Full-Length Practice MCAT Exam. This practice exam simulates the format of the current MCAT exam, including the MCAT testing interface (revised in 2018).</p>
                            <p>While you may wish to work some exams section-by-section, plan to take at least 3 exams in a test-like environment (over the course of a single day with only approved 10 and 30-minute breaks). </p>
                            <p>Please note that you cannot open multiple versions of the same test at the same time (for example in different browser windows). The system will let you attempt each test five times, but to avoid any errors in the system, please do not have two tests open at the same time.</p>
                            <p>This practice exam is a simulation. It has not been reviewed, endorsed, or approved by the AAMC. MCAT is a trademark of the AAMC.</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($step == 2)
                <div class="flexbox-item fill-area content flexbox-item-grow" style="background-color:#ffffff;">
                    <div class="fill-area-content flexbox-item-grow">
                        <div class="instructionsShell"><br><br><br><br>
                            <h1>Exam Category -1</h1><br>
                            <p>
                                <b>During the actual MCAT exam, you will be asked to review the name and picture.</b>
                            </p>
                            <p>
                                <b>If it is you, you will click the "Yes" button.</b><br>
                                <b>If it is not you, you will click the "No" button and contact the test administrator.</b>
                            </p>
                            <div id="name_check_box"><br><br><br>{{$examinee}}</div>
                            <p><b>
                                    Click on the "Next" button to continue with the test.</b>
                            </p>
                        </div>
                    </div> <!-- <div class="fill-area-content flexbox-item-grow"> -->
                </div>
                @endif
                @if($step == 3)
                <div class="fill-area-content flexbox-item-grow">
                    <div class="instructionsShell"><br>
                        <b>
                            <p>I agree to comply with the terms of the usage agreement. </p>
                            <p>Click NEXT below to begin your exam.</p>
                        </b>
                    </div>
                </div>
                @endif
                @if($step == 4)
                <div class="fill-area-content flexbox-item-grow">
                    <div class="instructionsShell"><br><h1 style="text-align:left; text-decoration: underline">Instructions</h1><p>This practice MCAT system is designed to simulate the functionality of the official MCAT. You may make use of the following features:</p></div>
                    <div id="accordion" class="instructionsShell ui-accordion ui-widget ui-helper-reset" role="tablist">
                        <h3 class="ui-accordion-header ui-corner-top ui-state-default ui-accordion-header-active ui-state-active ui-accordion-icons" role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="true" aria-expanded="true" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-minus"></span>Highlighting</h3>
                        <div class="ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content ui-accordion-content-active" id="ui-id-2" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false" style="display: block;">
                            <p>Highlighting can be a useful tool on the MCAT, as it allows you to identify important terms and phrases in the test content.</p>
                            <p>To highlight, drag your cursor to select the text that you would like to highlight. Then, click the “Highlight” button near the top left corner of the testing window.</p>
                            <img src="https://mcat.nextsteptestprep.com/images/testing/instructions1.png" class="guideimages">
                            <p>If you would like to remove highlights that you previously made, click the arrow to the left of the word “Highlight” to open a drop-down menu. From here, click “Remove Highlight” to switch to highlight removal mode. When in this mode, you will see a white square on the left of the word “Highlight,” as shown below. You can then select highlighted text and click “Highlight” to remove the highlights.</p>
                            <img src="https://mcat.nextsteptestprep.com/images/testing/instructions2.png" class="guideimages"><p></p>
                        </div>
                        <h3 class="ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-3" aria-controls="ui-id-4" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>Strikethrough</h3>
                        <div class="ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" id="ui-id-4" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="true" style="display: none;">
                            <p>Process of elimination is crucial on the MCAT. To strike out an answer choice, drag your cursor to highlight the text of the choice, then click “Strikethrough” in the upper left corner. Note that you can also strike out text from the passage or question stem if desired. To remove a strikethrough, highlight the text and click “Strikethrough” again.</p>
                        </div>
                        <h3 class="ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-5" aria-controls="ui-id-6" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>Flagging Questions</h3>
                        <div class="ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" id="ui-id-6" aria-labelledby="ui-id-5" role="tabpanel" aria-hidden="true" style="display: none;">
                            <p>You may flag questions that you would like to return to later. To do so, click the “Flag for Review” button near the top right of the screen. The icon will turn yellow to indicate that the question has been flagged.</p>
                            <p>You will then be able to identify the question as flagged on the Navigation and Section Review screens. On the Section Review screen, you will also have the option to review only flagged questions. The question will remain flagged until you click the “Flag for Review” button again, or until you click its flag icon on the Section Review screen.</p>
                        </div>
                        <h3 class="ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-7" aria-controls="ui-id-8" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>Navigation and Section Review Screens</h3>
                        <div class="ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" id="ui-id-8" aria-labelledby="ui-id-7" role="tabpanel" aria-hidden="true" style="display: none;">
                            <p>While initially clicking through the test, you will see a button on the bottom right that says “Navigation.” Clicking this button brings you to the table pictured below. From here, you can view which questions are unseen, incomplete, complete, and flagged for review, and you can click on any question number to be taken directly to that question.</p>
                            <img src="https://mcat.nextsteptestprep.com/images/testing/instructions3.png" class="guideimages">
                            <p>Once you have clicked through the exam, reached the final question of the section, and clicked “Next,” you will see the Section Review screen, shown below.</p>
                            <img src="https://mcat.nextsteptestprep.com/images/testing/instructions4.png" class="guideimages">
                            <p>From this screen, you can review all questions, or review only your incomplete or flagged questions. Alternatively, you can navigate directly to any question by clicking the question number. You can also add or remove flagged questions by clicking on the small flag icon. When you are ready to end the section, click End Section. Note that you will be asked to confirm that you would like to end the section before being moved onward.</p>
                        </div>
                        <h3 class="ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-9" aria-controls="ui-id-10" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>Keyboard Shortcuts</h3>
                        <div class="ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" id="ui-id-10" aria-labelledby="ui-id-9" role="tabpanel" aria-hidden="true" style="display: none;">
                            <p>While you may always work through the exam using the provided buttons, multiple keyboard shortcuts are available to you as an alternative. Using keyboard shortcuts may be faster than clicking the button(s) in some instances. The keyboard shortcuts are listed below:</p>
                            <strong>Alt+N</strong>  = Next question or next screen<br>
                            <strong>Alt+P</strong> = Previous question or previous screen<br>
                            <strong>Alt+V</strong> = Open navigation table<br>
                            <strong>Alt+T</strong> = Open periodic table<br>
                            <strong>Alt+C</strong> = Close navigation table or periodic table<br>
                            <strong>Alt+H</strong> = Highlight selected text<br>
                            <strong>Alt+S</strong> = Strike out selected text, or remove strikeout if the selected text was already stricken out<br>
                            <strong>Alt+F</strong> = Flag question for review, or remove flag if the question was already flagged<br>
                            <p>Keyboard shortcuts specific to the Section Review screen are listed below:</p>
                            <strong>Alt+A</strong> = Review All<br>
                            <strong>Alt+I</strong> = Review Incomplete<br>
                            <strong>Alt+R</strong> = Review Flagged Questions<br>
                            <strong>Alt+W</strong> = Return to Section Review screen after reviewing questions<br>
                            <strong>Alt+E</strong> = End Section<br>
                        </div>

                    </div>
                    <script>
                        $( function() {
                            $( "#accordion" ).accordion({
                                heightStyle: "content",
                                collapsible: true,
                                icons: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" },
                                classes: {
                                    // "ui-dialog": "periodicTableDIV",
                                    // "ui-dialog-titlebar": "PT_header",
                                    // "ui-dialog-title": "PT_header_title"
                                }
                            });
                        } );
                    </script>
                </div>
                @endif
                @if($step == 5)
                <div class="flexbox-item fill-area content flexbox-item-grow" style="background-color:#ffffff;">
                    <div class="fill-area-content flexbox-item-grow">


                        <div class="instructionsShell"><p>This practice test is not administered under the same secure conditions as the nationally administered official MCAT exam. Accordingly, the scores you achieve on this practice test should be considered an estimate only of the scores you might achieve on an actual MCAT administration. To get the most realistic possible assessment of your current MCAT score, purchase and take the official AAMC practice examinations. </p><p><i>Terms and Conditions</i></p>
                            <p>By proceeding past this screen, you agree to be bound by our terms and conditions.</p>
                            <ul>
                                <li>You may not share your unique login with anyone else. </li>
                                <li>You agree to use a supported browser and read the rest of the technical considerations below.</li>
                                <li>You agree not to reproduce, save, copy, or distribute any of the passages, questions, explanations, or any other component of the exams.</li>
                            </ul>
                            <p><i>Technical Considerations</i></p>
                            <p>Unlike the official MCAT, which is administered in a secure and standardized environment, students will access this test with a variety of technology, some of which we cannot support. </p>
                            <ul>
                                <li>You must use the current version of Chrome or Firefox. Older versions will not be supported.</li>
                                <li>Please maximize your browser window to complete these exams. Questions are formatted to appear correctly only on a full screen.</li>
                                <li><i>Tablet devices or mobile devices are not supported.</i> While parts of the test will work, others will not. </li>
                                <li>You should plan to complete an entire timed section (if not the whole test) in a single sitting. If you must, you may use the “pause” feature within a section. If you close the window, some of your progress on a given section may not be saved, though you can use the “Continue” feature from the main page to recover from the last saved screen.</li>
                                <li>If you have technical issues after confirming that you are using a supported browser, please email us at <a href="mailto:mcat@nextsteptestprep.com">mcat@nextsteptestprep.com</a>.</li>
                            </ul>
                            <p>
                                This practice test has not been reviewed, approved, or endorsed by the AAMC. MCAT is a trademark of the AAMC. The content, format, and software for these exams are copyright Next Step Test Preparation LLC and cannot be reproduced in any way.
                            </p></div>


                    </div> <!-- <div class="fill-area-content flexbox-item-grow"> -->
                </div>
                @endif
                @if($step == 6)
                <div class="flexbox-item fill-area content flexbox-item-grow" style="background-color:#ffffff;">
                    <div class="fill-area-content flexbox-item-grow flex-container">
                        <div class="flex-child" id="test_infoPanel_main_left">
                            <div id="left_content_studentmarkedDIV"><b>Passage 1 (Questions 1-5)</b><br><br>    A hypersaline body of water contains high concentrations of sodium chloride (salt) and other water-soluble ionic compounds such as calcium sulfate (gypsum). The salt levels exceed those found in ocean water (which contains 3.5% sodium chloride, by mass) and are often associated with flora and fauna that are specifically adapted to these extreme conditions. There is considerable interest in species that can survive under such conditions because they may represent conditions for life on other worlds. <br><br> The best-known hypersaline lakes are located in hydrologically isolated environments, in which water inflow is in equilibrium with evaporation. Examples include the Dead Sea (DS) in the Middle East and the Great Salt Lake (GSL) in Utah. Salt concentrations in the DS are greatest in the southern section and approach saturation (Figure 1a). Salt concentrations in the GSL range from levels similar to ocean water up to saturation. <br><br> Swimming in salty water is a significantly different experience than swimming in fresh water. The average human body has a slightly greater density (1.01 kg/L) than fresh water, and people must usually swim to stay afloat in a body of fresh water (e.g. Lake Michigan). However, adding salt to water increases the density of the liquid, resulting in an increase in buoyant force. The salt also affects other properties, including colligative properties, such as reductions of vapor pressure (Figure 1b), melting point, and solubility of gases. <br><br> <img src="https://mcat.nextsteptestprep.com/uploads/_textboxio/5eebfd7f04915fe18cd7a83f31c51c47_image.png" style="width: 425px; height: 409px; margin: 0px auto; display: block;">   <img src="https://mcat.nextsteptestprep.com/uploads/_textboxio/bd73d3c905a21f73adb6052996f1acde_image.png" style="width: 467px; height: 356px; margin: 0px auto; display: block;"> <br><br>  <strong>Figure 1</strong> The solubility curves for some ionic compounds in water as a function of temperature (top) and the phase diagram (bottom) of pure water (thin black line) and salt water (thick gray line) <br><br> Hypersaline bodies of water tend to be fairly sterile, containing only highly adapted forms of life. Brine shrimp are the most notable aquatic life form in the GSL. These shrimp feed on algae by mastication, using mandibles to create a bolus, which, after injection into the mouth, travels down the equivalent of an esophagus to the stomach. The shrimp ingest a lot of salt during this process, which is excreted through the branchia. In addition, anaerobic halophiles can be found, namely fermentative, sulfate-reducing, homoacetogenic, phototrophic, and methanogenic bacteria. <br><br></div>  </div> <!--1-->

                        <div class="flex-child" id="test_infoPanel_main_right">
                            <br><form action="https://mcat.nextsteptestprep.com/mcattest/launch/37/t/137/0/x" method="post" accept-charset="utf-8" id="myform" name="myform"><div class="questionblocks" name="questionblocks" id="572"><p class="questionTitle" id="question_number_h">Question 1</p><br><div id="questiondiv" style="margin-bottom:5px;padding-left:50px;"><span>    What is the approximate molarity of sodium chloride in ocean water, if the density of ocean water is 1.028 kg/L?</span><br><br></div><input type="hidden" name="questiontimer" id="questiontimer" value="4959"><input name="572" type="hidden" value=""><table><tbody><tr><td valign="top" width="75px;"></td><td style="padding-right:5px;padding-top:4px" valign="top"><input type="radio" name="572" value="answer1_text" id="572_1" class="answerradio"></td><td style="padding-top:2px;padding-left: 1.5em; text-indent:-1.5em;"><label for="572_1"><span id="a1"><strong> A. </strong><span>    0.026 M</span><br><br></span></label></td></tr><tr><td valign="top" width="75px;"></td><td style="padding-right:5px;padding-top:4px" valign="top"><input type="radio" name="572" value="answer2_text" id="572_2" class="answerradio"></td><td style="padding-top:2px;padding-left: 1.5em; text-indent:-1.5em;"><label for="572_2"><span id="a2"><strong> B. </strong><span>    0.62 M</span><br><br></span></label></td></tr><tr><td valign="top" width="75px;"></td><td style="padding-right:5px;padding-top:4px" valign="top"><input type="radio" name="572" value="answer3_text" id="572_3" class="answerradio"></td><td style="padding-top:2px;padding-left: 1.5em; text-indent:-1.5em;"><label for="572_3"><span id="a3"><strong> C. </strong><span>    0.96 M</span><br><br></span></label></td></tr><tr><td valign="top" width="75px;"></td><td style="padding-right:5px;padding-top:4px" valign="top"><input type="radio" name="572" value="answer4_text" id="572_4" class="answerradio"></td><td style="padding-top:2px;padding-left: 1.5em; text-indent:-1.5em;"><label for="572_4"><span id="a4"><strong> D. </strong><span>    9.6 M</span><br><br></span></label></td></tr></tbody></table>
                                    <script>


                                        function displayFeedbackLayer()
                                        {

                                            var questionID = 572
                                            document.getElementById("overlay1").style.visibility='visible';
                                            document.getElementById("feedback_div").style.visibility='visible';

                                            document.getElementById('framebox').contentWindow.setQuestionID(questionID);

                                        }

                                        function hideFeedbackLayer()
                                        {
                                            document.getElementById("overlay1").style.visibility='hidden';
                                            document.getElementById("feedback_div").style.visibility='hidden';
                                        }

                                    </script></div>  </form></div> <!--2-->


                    </div> <!-- <div class="fill-area-content flexbox-item-grow"> -->
                </div>
                @endif

        <!-- Control Bar Layers -->
        <div id = "test_controlBar"  class="flexbox-item footer">
            <div id = "test_controlBar_left"  style = "float:left;">
                &nbsp;
            </div>
            <div id = "test_controlBar_right"  style = "float:right;">
                <a id = "nextButton" class = "controlBar_buttons_right yellowHover" href="{{url('onlineExams/1/start/'.$step)}}">
                    <div style = "float:left"><u>N</u>ext&nbsp;</div>
                    <img id = "nextButton_img" src = "https://mcat.nextsteptestprep.com/images/testing/rightarrow_white.png" style = "margin-top:1px;margin-left:0px;margin-right:5px;">
                </a>
            </div> <!-- test_controlBar_right -->
        </div> <!-- <div id = "test_controlBar"  class="flexbox-item footer">-->
    </div> <!--container-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.1/mousetrap.js"></script>

<script>
    Mousetrap.bind('alt+n', function(e) {
        $('#nextButton').click();
    });
</script>
</body>
</html>

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

