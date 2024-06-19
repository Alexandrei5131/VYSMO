<?php

include('../../database.php');

//colors
    $sqlAllColors = mysqli_query($connection, "SELECT * FROM theme_color");
    $colors = array();

// Check if there are rows returned
if (mysqli_num_rows($sqlAllColors) > 0) {
    while ($row = mysqli_fetch_assoc($sqlAllColors)) {
        $colors[] = $row;
    }
}



header("Content-type: text/css; charset: UTF-8");


?>

/********** Template CSS **********/
@import url("https://fonts.googleapis.com/css2?family=Lato&display=swap");
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600&display=swap');


:root {
    --primary: #ff9900;
    --light: #F3F6F9;
    --dark: #191C24;
    --main-color:#2980b9;
    --secondary-color:#666;
    --black:#444;
    --bg:#fff;
    --light-bg:#eee;
    --light-color:#666;
    --border:1px solid rgba(0,0,0,.1);
    --box-shadow:0 5px 10px rgba(0,0,0,.1);
}

.head1{
  /* box-shadow: 2px 2px; 
  background-color: #ff9900 !important;
    background-color: hsla(227, 38%, 62%, 0.79);
  background-color: rgb(65, 158, 65) ;*/
  background-color: <?php echo $colors[0]['colorBG']?> ;
}

.visitInfo{
    /*  background-color: #6f42c1 !important;
        background-color: hsla(227, 38%, 62%, 0.79) !important ;*/

      background-color: <?php echo $colors[0]['colorBG1']?> ;
  }
  .visitInfo1{
    /*  background-color: #6f42c1 !important;
        background-color: hsla(227, 38%, 62%, 0.79) !important ;*/

      background-color: <?php echo $colors[0]['colorBG1']?> ;
  }
.defaultColor{
    background-color: <?php echo $colors[0]['colorBG1']?> ;
}

.sidebar{
    /*   background-color: #007bff !important;
    background-color: rgb(76, 143, 76) !important;
        background-color: hsla(227, 78%, 77%, 0.68) !important;
    background-color: rgb(29, 87, 29) !important;*/  
    background-color: <?php echo $colors[0]['color']?> ;

   }
   .content {
    margin-left: 250px;
    min-height: 100vh;
    background-color: <?php echo $colors[0]['colorBG2']?> ;
    transition: 0.5s;
    align-items: left !important;

}
   #QRCode{
    border: 2px solid rgb(65, 157, 158);
  }
   .dashBorder,.dashBorder1{
    padding: 0 !important;
    border-radius: 7px;
}
.bodyContent{
    padding: 0 !important;
    border-radius: 7px;
    width: 98%;
}
.itemContain,.itemContainQRScan,.tableApproveScan,.bodyContent,.dashBorder,.dashBorder1,.bodyContentVisitList{
    border: solid;
    border-color: <?php echo $colors[0]['colorBG1']?>;
}
a {
    
}
.colorDefault{
    font-size: small;
    color: #eee;
    text-align: center;
    padding-top: 13px;
}
.bodyContentVisitList{
    padding: 0 !important;
    border-radius: 7px;
    width: 98%;
}
.tableRequestScan{
    border: solid;
    border-color: #ff9900;
}

#head{
    color: #ff9900 !important;
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    font-weight: 800;
    text-shadow: 2px 2px black;
}

.timeInput{
    border:none;
    text-align: center;
}
.labelBlend{
	mix-blend-mode: difference; 
}
/*color changer*/
#theme-open{
    position: fixed;
    top:15px; right:20px;
    font-size: 40px;
    color:var(--black);
    cursor: pointer;
    z-index: 10;
  }
  #theme-open1{
    position: fixed;
    top:15px; right:20px;
    font-size: 40px;
    color:var(--black);
    cursor: pointer;
    z-index: 10;
  }
  
.themes-container{
    position:fixed;
    top:0; right:-100%;
    background:var(--bg) !important;
    border-left: var(--border);
    height:100vh;
    width:400px;
    z-index: 1000;
    text-align: right;
    padding:20px;
    overflow-y: auto;
  }
  
  .themes-container.active{
    right:0;
  }
  
  .themes-container #theme-close{
    font-size: 40px;
    color:var(--black);
    cursor: pointer;
  }
  
  .themes-container h3{
    text-align: center;
    color:var(--black);
    border-top: var(--border);
    border-bottom: var(--border);
    padding:15px 0;
    margin:20px 0;
  }
  
  .themes-container .theme-toggler{
    display:flex;
    align-items: center;
    justify-content: center;
  }
  
  .themes-container .theme-toggler span{
    color:var(--light-color);
  }
  
  .themes-container .theme-toggler .toggler{
    height:40px;
    width:100px;
    border-radius: 50px;
    background:var(--light-bg);
    position: relative;
    cursor: pointer;
    border:var(--border);
    margin:0 10px;
  }
  
  .themes-container .theme-toggler .toggler::before{
    content:'';
    position: absolute;
    top:5px; left:5px;
    height:28px;
    width:28px;
    border-radius: 50%;
    background:#fff;
    transition:.2s linear;
  }
  
  .themes-container .theme-toggler.active .toggler::before{
    left:65px;
  }
  
  .themes-container .theme-colors{
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap:15px;
  }
  .themes-container .theme-colorsBG{
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap:15px;
    }
    .themes-container .theme-colorsBG1{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap:15px;
      }  
      .themes-container .theme-colorsBG2{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap:15px;
      }  
  .themes-container .theme-colors .color{
    border-radius: 5px;
    height:45px;
    width:45px;
    cursor: pointer;
  }
  .themes-container .theme-colorsBG .colorBG{
      border-radius: 5px;
      height:45px;
      width:45px;
      cursor: pointer;
    }
    .themes-container .theme-colorsBG1 .colorBG1{
        border-radius: 5px;
        height:45px;
        width: 45px;;
        cursor: pointer;
      }
      .themes-container .theme-colorsBG2 .colorBG2{
        border-radius: 5px;
        height:45px;
        width: 45px;;
        cursor: pointer;
      }
  .themes-container .theme-colors .color:hover{
    opacity: .5;
  }
  
  .themes-container .theme-colorsBG .colorBG:hover{
      opacity: .5;
    }
    .themes-container .theme-colorsBG1 .colorBG1:hover{
        opacity: .5;
      }
      .themes-container .theme-colorsBG2 .colorBG2:hover{
        opacity: .5;
      }
/*COlor changer*/


#inlineFormInputName{
    width:25%;
}

/*** Spinner ***/
#spinner {
    opacity: 0;
    visibility: hidden;
    transition: opacity .5s ease-out, visibility 0s linear .5s;
    z-index: 99999;
}

#spinner.show {
    transition: opacity .5s ease-out, visibility 0s linear 0s;
    visibility: visible;
    opacity: 1;
}


/*** Button ***/
.btn {
    transition: .5s;
}

.btn.btn-primary {
    color: #FFFFFF;
}

.btn-square {
    width: 38px;
    height: 38px;
}

.btn-sm-square {
    width: 32px;
    height: 32px;
}

.btn-lg-square {
    width: 48px;
    height: 48px;
}

.btn-square,
.btn-sm-square,
.btn-lg-square {
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: normal;
    border-radius: 50px;
}


/*** Layout ***/
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 250px;
    height: 100vh;

    transition: 0.5s;
    z-index: 999;
}


#accept,#decline{
    width: 100%;
}
#accept:hover {
    background-color: green;
  }
#decline:hover {
    background-color: red;
  }
#frontID,#backID{
    text-align: center;
}
#validFrontID,#validBackID,#selfieID,#portraitSelfieID{
    border-radius: 2%;
}
.navbar-brand{
    font-size: 34px;
    text-shadow: 2px 2px gray;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
h1,h2,h3,h4,h5,h6,span,label,a,th,td,span,button,.nav-item,.btn{
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;

}

.guardLogo{
    width: 80px;
    height: 60px;
}
.namePerson,.nameOffice,.nameEvent{
    text-align: center;
    font-size: x-large;
}
.borderVisit{
    border: solid 2px;
    border-radius: 15px;

}
.navBorder{
    border: solid 2px !important;
    background-color: green;

}
.chanceVisit{
    background-color: #ff9900;
}
.chanceVisitHead{
    font-size: 28px;
    color: black;
    font-family: klavika;
}
.visitInfoPerson,.visitInfoOffice{
    background-color: green !important;
}
.formVisitPerson,.formVisitOffice{
    border: solid;
    border-color: green;
}
label,span,button,a{
    font-family: 'Times New Roman', Times, serif;
}

#inactiveBody,#activeBody,#deleteBody{
    font-size: 24px;
    color: black;
    font-family: klavika;
}
#gender,.nationality{
    background-color: white !important;
}
#clear{
    margin-left: 5px !important;
}
#nameToVisLabel{
    height: 24px !important;
}



.logsList{
    padding: 0 !important;
    border: solid;
    border-radius: 7px;
    border-color: rgb(76, 143, 76) ;
    width: 100%;
}
.bodyContent1{
    padding: 0 !important;
    border: solid 2px;
    border-radius: 7px;
    border-color: green;
}

.bodyContent2{
    padding: 0 !important;
    border: solid 2px;
    border-radius: 7px;
    border-color: #ff9900;
}
.todayHead{
    border: solid 2px;
    border-color: green !important;
    background-color: green;
}
.chanceHead{
    border: solid 2px;
    border-color: #ff9900 !important;
}
.requestVisit{

    background-color: #ff9900;
}
#forms {
    list-style-type: none !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
    background-color: #333 !important;
    border: solid 2px !important;
    border-radius: 20px !important;

  }
  li {
    float: left;
  }
  
  li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 14px;
    text-decoration: none;
    font-size: medium !important;
  }
  
  li a:hover, .active {
    background-color: #111;
  }
  ul {
    list-style-type: none;
    padding: 0;
} 
.sidebar,.content {
    overflow-x: hidden; /* Hide scrollbars */
  }
.sidebar::-webkit-scrollbar{
    display: none;
  }

.visitForm{
    min-height: 470px !important;
}
.addEval{
    margin-top: 4.5%;
    width: 20% !important;
}
.questionsForm{
    left:20% !important;
    transform: translate(20%);
    background-color: white !important;
    
}
.questionsFormNote{
    left:20% !important;
    transform: translate(20%);
}
.btnForm{
    left: 21.5%;
    transform: translate(21.5%);
}
.clearBtn{
    color: #6f42c1;
}
.clearBtn:hover{
    background-color: lightgray;
    color: #6f42c1;
}
.questionLetter{
    font-family: 'docs-Roboto', Helvetica, Arial, sans-serif;
}
.answerLetter{
    font-family:  Roboto,Arial,sans-serif;
    color: #202124;;
}
.evalFormBody{
    background-color: #f0ebf8 !important;
}
#openAccountQR,#qrScanner{
    border-radius: 20px;
}
#vysLogo{
    width: 60%;
    transform: translate(37%);
}
#vysLogoUserProfile{
    width: 70%;
}
.timeInAccount{
    font-size: 24px !important;
}
.qrPrint{
    width: 75%;
}

.logoQR{
    width: 100%;
}
.header{
    margin-left: 5%;
}
.logoQRBorder,.vysLogoBorder{
    width: 10%;
    margin: 0   ;
}
.logoQRBorder1,.vysLogoBorder1{
    width: 10%;
    margin: 0   ;
}
.logoQR,.vysLogo{
    width: 100%;
}
.logoQR1,.vysLogo1{
    width: 100%;
}
.headerTitleMain{
    margin-top: 1% !important;
  }
  .headerTitleMain1{
    margin-top: 10px !important;
  }
#datePrinted{
    margin-top: 14.6% !important;
}
#footer{
    margin-top: 12%;
    margin-left: 0;
    margin-right: 0;
    background-color: lightblue;
    font-size: 12px;
    font-family: 'Courier New', Courier, monospace;
    color: black;

}

.display-date {
    text-align: center;
    font-size: 18px;
  }
  .display-time {
    font-size: 18px;
    font-weight: bold;
  }
  .printable{
    background-color: white;
  }
  #downloadReportTable{
    overflow-y: auto;
}
.guardLogo{
    width: 60px;
}
.expected,.chance,.successful, .totalVisitors{
    width: 100%;
}
.expected{
    background-color:rgb(0,176,186);
}
.chance{
    background-color:rgb(223, 159, 39);
}
.successful{
    background-color:rgb(110, 169, 50);
}
.totalVisitors{
    background-color:rgb(207,248,0);
}
#tableLogsList{
max-height: 200px;    
}
#tableGuardSetting{
    max-height: 250px;
}
.noteEval{
    font-size: 18px;
}
.center{
    width: 90%;
margin: 0;
position: absolute;
top: 40%;
left: 50%;
-ms-transform: translate(-50%, -50%);
transform: translate(-50%, -50%);
}
.centerReset{
    width: 100%;
margin: 0;
position: absolute;
top: 50%;
left: 50%;
-ms-transform: translate(-50%, -50%);
transform: translate(-50%, -50%);
overflow: hidden;
}
.headerTitle2{
    font-size: 25px;
    font-weight: bold;
    font-weight: 800;
    color: black;
}
.textErrorDesign{
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    font-weight: 800;
    font-size: 22px;

}
.neustHeader{
    font-size: 2vw !important;
}
.searchBtnVisitationList{
    width: 25% !important;
}

/*FOR LARGE SCREEN*/
@media only screen and (min-width: 1537px){
    .sidebar {
        margin-left: 0;
    }

    .sidebar.open {
        margin-left: -250px;
    }
    .sidebar-toggler{
        display: none !important;
    }
    .content .navbar .navbar-brand{
    font-size:2vw;
}

    .content.open {
        width: 100%;
        margin-left: 0;
    }
    #QRCode{
        width:500px;
      }
    #validFrontID,#validBackID,#selfie{
        width: 98%;
        height: 290px;
    }
    #validFrontIDportrait,#validBackIDportrait,#portraitSelfieID{
        width: 70%;
        height: 450px;
        margin-left: 15% !important;
    }
    #table{
        height: 500px;
    }
    #tableResults{
        height: 710px;
    }
    #tableResponses{
        height: 690px;
    }
    #tableEvalQuestion{
        height: 710px;
    }
    #tableApprovalList{
        height: 680px;
    }
    #tableGuardList{
        height: 725px;
    }
    #tableEventList{
        height: 700px;
    }
    #tableDestination{
        height: 700px;

    }
    #tableVisitHistory{
        height: 690px;

    }
    #tableOpenVisitHistory{
        height: 690px;

    }
    #downloadReportTable{
        height: 630px !important;
    }
    
    #tableEvalListAdmin,#tableEvalListVisitor{
        height: 680px;
    }
    #table1 {
        max-height: 210px;
    }
    #tableVisitList{
        max-height: 680px;
    }

    
    .lg-view{
        display:inline-block;
     }
 
     .sm-view,.close1{
        display:none;
     }
    
    .sname,.mname{
        width: 8%;
    }

    .lname,.fname{
        width:28% ;
    }
    
    .purpose,.office,.venue,.event{
        width: 35%;
    }
   .person,.relationship,.date,.numVis{
        width: 21.5% !important;
    }
    .dept{
        width: 21%;
    }
    .visitFormCopy{
        width: 100% !important;
    }
    .currentPwd{
        width: 50%;
        left: 50%;
        transform: translate(50%);
    }
    .newPwd{
        width:50%;
        left: 50%;
        transform: translate(50%);
    }
    .searchBtnEvent{
        width: 50% !important;
    }
    .smScreen{
        display: none;
    }
    .smScreenEvent{
        display: none;
    }
    .piegraph{
        width: 30%;
    }
    .linegraph{
        width: 70%;
    }
}

/*FOR MEDIUM SCREEN*/
@media only screen and (min-width: 1367px) and (max-width:1536px){
    .sidebar {
        margin-left: 0;
    }

    .sidebar.open {
        margin-left: -250px;
    }
    .sidebar-toggler{
        display: none !important;
    }

    .content .navbar .navbar-brand{
    font-size:2vw;
}
    .content.open {
        width: 100%;
        margin-left: 0;
    }
    #QRCode{
        width:400px;
        height:100%;
      }
    #validFrontID,#validBackID,#selfie{
        width: 98%;
        height: 260px;
    }
    #validFrontIDportrait,#validBackIDportrait,#portraitSelfieID{
        width: 75%;
        height: 350px;
        margin-left: 12% !important;
    }
    #table{
        height: 360px;
    }
    #tableResults{
        height: 485px;
    }
    #tableResponses{
        height: 470px;
    }
    #tableEvalQuestion{
        height: 490px;
    }
    #tableApprovalList{
        height: 460px;
    }
    #tableGuardList{
        height: 500px;
    }
    #tableEventList,#tableDestination{
        height: 480px;
    }
    #tableVisitHistory{
        height: 470px;

    }
    #tableOpenVisitHistory{
        height: 470px;

    }
    #downloadReportTable{
        height: 450px !important;
    }
    #tableEvalListAdmin,#tableEvalListVisitor{
        height: 460px;
    }
    #table1 {
        max-height: 210px;
    }
    #tableVisitList{
        max-height: 450px;
    }
     


    .lg-view{
        display:inline-block;
     }
 
     .sm-view,.close1{
        display:none;
     }
     .sname,.mname{
        width: 12%;
    }

    .lname,.fname{
        width:36% !important;
    }
    
    .purpose,.office,.venue,.event{
        width: 42%;
    }
   .person,.relationship,.date,.numVis{
        width: 28.5% !important;
    }
    .dept{
        width: 27%;
    }
    #forms{
        font-size: smaller !important;
    }
    .visitFormCopy{
        width: 100% !important;
    }
    .currentPwd{
        width: 50%;
        left: 50%;
        transform: translate(50%);
    }
    .searchBtnEvent{
        width: 75% !important;
    }
.smScreen{
    display: none;
}
.smScreenEvent{
    display: none;
}
.piegraph{
    width: 30%;
}
.linegraph{
    width: 70%;
}

}
/*FOR SMALL SCREEN*/

@media only screen and (min-width: 992px) and (max-width:1366px)  {
    .sidebar {
        margin-left: 0;
    }

    .sidebar.open {
        margin-left: -250px;
    }
    .sidebar-toggler{
        display: none !important;
    }
.passSettingLabel{
    font-size: 30px;
    margin-left: 15px;
}
.content .navbar .navbar-brand{
    font-size:2vw;
}
    .content.open {
        width: 100%;
        margin-left: 0;
    }
    #qrScanner{
        height: 360px;
    }
    #QRCode{
        width:350px;
        height:100%;
      }

    #validFrontID,#validBackID,#selfie{
        width: 100%;
        height: 250px;
    }
    #validFrontIDportrait,#validBackIDportrait,#portraitSelfieID{
        width: 75%;
        height: 350px;
        margin-left: 12% !important;
    }
    #table{
        height: 360px;
    }
    #tableResults{
        height: 500px;
    }
    #tableResponses{
        height: 490px;
    }
    #tableEvalQuestion{
        height: 500px;
    }
    #tableApprovalList{
        height: 480px;
    }
    #tableGuardList{
        height: 520px;
    }
    #tableEventList,#tableDestination{
        height: 480px;
    }
    #tableVisitHistory{
        height: 485px;

    }
    #tableOpenVisitHistory{
        height: 485px;

    }
    #downloadReportTable{
        height: 480px !important;
    }
    #tableEvalListAdmin,#tableEvalListVisitor{
        height: 480px;
    }
    
    #table1 {
        max-height: 235px;
    }
    #tableVisitList{
        max-height: 470px;
    }


    .lg-view{
        display:inline-block;
     }
 
     .sm-view,.close1{
        display:none;
     }

     .sname,.mname{
        width: 15%;
    }

    .lname,.fname{
        width:37.5% ;
    }
    
    .purpose,.office,.venue,.event{
        width: 48%;
    }
   .person,.relationship,.date,.numVis{
        width: 29% !important;
    }
    .dept{
        width: 32%;
    }
    .visitFormCopy{
        width: 100% !important;
    }
    .currentPwd{
        width: 50%;
        left: 50%;
        transform: translate(50%);
    }
    .searchBtnEvent{
        width: 100% !important;
    }
    .smScreenEvent{
        display: none;
    }
    .smScreen{
        display:none;
    }

    .piegraph{
        width: 30%;
    }
    .linegraph{
        width: 70%;
    }
}
/*FOR MOBILE*/
@media only screen and (max-width: 991.98px) {
    .sidebar {
        margin-left: -250px;
    }
    .searchBtnVisitationList{
        width: 50% !important;
    }
    .neustHeader{
        font-size: 4vw !important;
    }
    .sidebar.open {
        margin-left: 0;
    }

    .answersEval{
        margin-left: 50px;
    }
    .content {
        width: 100%;
        margin-left: 0;
    }
    .sidebar-toggler{
        margin-left: 1px;
    }
    .navbar-brand{
        font-size: small;
    }
    #QRCode{
        width:88%;
      }
      
    #validFrontID,#validBackID,#selfie{
        width: 100%;
        height: 250px;
    }

    #validFrontIDportrait,#validBackIDportrait,#portraitSelfieID{
        width: 100%;
        height: 350px;
 
    }
    #tableVisitList{
        height: 370px;
    }
    #openAccountQR,#qrScanner{
        width: 100% !important;
    }
    .lg-view{
        display:none;
     }
 
     .sm-view{
        display:inline-block;
     }
    
     .numVisDate{
        justify-content: center !important;
     }
     .purpose,.venue,.event,.date,.numVis,.person,.relationship,.fname,.mname,.lname,.sname,.dept{
        width: 110% !important;
    }
    li a {
        font-size: smaller !important;
    }

    .typeID,.houseNum,.nationalityForm,.fNameForm,.lNameForm,.mNameLbl,.mNameForm,.houseNumForm,.streetForm,.numForm,.typeID,.sNameForm,.genderForm{
        width: 100% !important;
    }

    .lgScreenEvent{
        display: none;
    }
    .lgScreen{
        display: none;
    }
    .searchBtn{
        width: 100% !important;
    }
    .searchBtnEvalList{
        width: 50% !important;
    }
    .checkEval{
        width: 100%;
        left: 0%;
        transform: translate(0%);
    }
    .printable{
        width: 100% !important;
    }
.headerTitle{
    font-size: 12px;
}
.headerTitle1{
    font-size: 9px;
}
.headerTitle2{
    font-size: 13px;
    margin-bottom: 5px;
}
.logoQRBorder1,.vysLogoBorder1{
    width: 35%;
    margin: 0   ;
}
.logoQR1,.vysLogo1{
    width: 100%;
}
.logoQR{
    transform: translate(0%,50%);
}
.piegraph{
    margin-top: 8%;
}
.noteEval{
    font-size: 10px !important;
}
.center{
top: 50%;
}
.headerTitleMain1{
    margin-top: 30px !important;
  }
.textErrorDesign{
    font-size: 14px;
}
}


/*** Navbar ***/

.sidebar .navbar .navbar-nav .nav-link {
    padding: 7px 20px;
    color: var(--dark);
    font-weight: 500;
    border-left: 3px solid var(--light);
    border-radius: 0 30px 30px 0;
    outline: none;
}

.sidebar .navbar .navbar-nav .nav-link:hover,
.sidebar .navbar .navbar-nav .nav-link.active {
    color: var(--primary);
    background: #FFFFFF;
    border-color: var(--primary);
}

.sidebar .navbar .navbar-nav .nav-link i {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #FFFFFF;
    border-radius: 40px;
}

.sidebar .navbar .navbar-nav .nav-link:hover i,
.sidebar .navbar .navbar-nav .nav-link.active i {
    background: var(--light);
}

.sidebar .navbar .dropdown-toggle::after {
    position: absolute;
    top: 15px;
    right: 15px;
    border: none;
    content: "\f107";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    transition: .5s;
}

.sidebar .navbar .dropdown-toggle[aria-expanded=true]::after {
    transform: rotate(-180deg);
}

.sidebar .navbar .dropdown-item {
    padding-left: 25px;
    border-radius: 0 30px 30px 0;
}

.content .navbar .navbar-nav .nav-link {
    margin-left: 25px;
    padding: 12px 0;
    color: var(--dark);
    outline: none;
}

.content .navbar .navbar-nav .nav-link:hover,
.content .navbar .navbar-nav .nav-link.active {
    color: var(--primary);
}

.content .navbar .sidebar-toggler,
.content .navbar .navbar-nav .nav-link i {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #FFFFFF;
    border-radius: 40px;
}

.content .navbar .dropdown-toggle::after {
    margin-left: 6px;
    vertical-align: middle;
    border: none;
    content: "\f107";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    transition: .5s;
}

.content .navbar .dropdown-toggle[aria-expanded=true]::after {
    transform: rotate(-180deg);
}

@media (max-width: 575.98px) {
    .content .navbar .navbar-nav .nav-link {
        margin-left: 15px;
    }
}


