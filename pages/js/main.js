//for no dev tool

(function ($) {
    "use strict";

    // Spinner
    var delay = 500;
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, delay);
    };
    spinner();
    
   

    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });
    $('.close1').click(function () {
      $('.sidebar, .content').toggleClass("open");
      return false;
  });

    
})(jQuery);
let theme = document.querySelector('.themes-container');

document.querySelector('#theme-open').onclick = () =>{
    theme.classList.add('active');
    document.body.style.paddingRight = '350px';
}

document.querySelector('#theme-close').onclick = () =>{
    theme.classList.remove('active');
    document.body.style.paddingRight = '0px';
}

document.querySelectorAll('.theme-colors .color').forEach(color => {
    color.onclick = () => {
        var background = color.style.background;
        document.querySelector('.sidebar').style.setProperty('background', background);

        // Add an AJAX request to update the theme color in the database
        updateThemeColor(background, 'color');
    }
});

document.querySelectorAll('.theme-colorsBG .colorBG').forEach(color => {
    color.onclick = () => {
        var background = color.style.background;
        document.querySelector('.head1').style.setProperty('background', background);

        // Add an AJAX request to update the theme color in the database
        updateThemeColor(background, 'colorBG');
    }
});


document.querySelectorAll('.theme-colorsBG1 .colorBG1').forEach(color =>{
color.onclick = () => {
    var background1 = color.style.background;

    document.querySelector('.bodyContent,.itemContain,.itemContainQRScan,.tableApproveScan,.dashBorder,.bodyContentVisitList').style.setProperty('border-color', background1);  
    updateThemeColor(background1, 'colorBG1');
    document.querySelector('.visitInfo').style.setProperty('background-color', background1);
    document.querySelector('.visitInfo1').style.setProperty('background-color', background1);
    document.querySelector('.dashBorder1').style.setProperty('border-color', background1);

    // Add an AJAX request to update the theme color in the database


  }
});

document.querySelectorAll('.theme-colorsBG2 .colorBG2').forEach(color =>{
color.onclick = () => {
  var background4 = color.style.background;
  document.querySelector('.content').style.setProperty('background-color', background4);

  // Add an AJAX request to update the theme color in the database
  updateThemeColor(background4, 'colorBG2');

}
});
function clearForm() {
  document.getElementById("myForm").reset();
}

//Preview front img
function frontPreviewImage() {
    var fileInput = document.getElementById('frontID');
    var image = document.getElementById('validFrontID');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function(e) {
      image.src = e.target.result;
    }
  
    reader.readAsDataURL(file);
  }
  function frontPreviewImage1() {
    var fileInput = document.getElementById('frontIDPortrait');
    var image = document.getElementById('validFrontIDportrait');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function(e) {
      image.src = e.target.result;
    }
  
    reader.readAsDataURL(file);
  }

  function resetFrontImage() {
    var image = document.getElementById('validFrontID');
    var originalSrc = image.getAttribute('data-original');
    image.src = originalSrc;
    var fileInput = document.getElementById('frontID');
    fileInput.value = '';
  
    event.preventDefault();
  }
  
  function resetFrontImage1() {
    var image = document.getElementById('validFrontIDportrait');
    var originalSrc = image.getAttribute('data-original');
    image.src = originalSrc;
    var fileInput = document.getElementById('frontIDPortrait');
    fileInput.value = '';
  
    event.preventDefault();
  }
  

//Preview back img
  function backPreviewImage() {
    var fileInput = document.getElementById('backID');
    var image = document.getElementById('validBackID');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function(e) {
      image.src = e.target.result;
    }
  
    reader.readAsDataURL(file);
  }
  function backPreviewImage1() {
    var fileInput = document.getElementById('backIDPortrait');
    var image = document.getElementById('validBackIDportrait');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function(e) {
      image.src = e.target.result;
    }
  
    reader.readAsDataURL(file);
  }
  function resetBackImage() {
    var image = document.getElementById('validBackID');
    var originalSrc = image.getAttribute('data-original');
    image.src = originalSrc;
    var fileInput = document.getElementById('backID');
    fileInput.value = '';
  
    event.preventDefault();
  }
  
  function resetBackImage1() {
    var image = document.getElementById('validBackIDportrait');
    var originalSrc = image.getAttribute('data-original');
    image.src = originalSrc;
    var fileInput = document.getElementById('backIDPortrait');
    fileInput.value = '';
  
    event.preventDefault();
  }
//Preview selfie
  function selfiePreview() {
    var fileInput = document.getElementById('selfieID');
    var image = document.getElementById('selfie');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function(e) {
      image.src = e.target.result;
    }
  
    reader.readAsDataURL(file);
  }
//Reset Selfie img
  function resetSelfie() {
    var image = document.getElementById('selfie');
    var originalSrc = image.getAttribute('data-original');
    image.src = originalSrc;
    var fileInput = document.getElementById('selfieID');
    fileInput.value = '';
  
    event.preventDefault();
  }


  //Preview 2x2
  function pic2x2Preview() {
    var fileInput = document.getElementById('pic2x2');
    var image = document.getElementById('pic');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onload = function(e) {
      image.src = e.target.result;
    }
  
    reader.readAsDataURL(file);
  }
//Reset 2x2 img
  function resetPic2x2() {
    var image = document.getElementById('pic');
    var originalSrc = image.getAttribute('data-original');
    image.src = originalSrc;
    var fileInput = document.getElementById('pic2x2');
    fileInput.value = '';
  
    event.preventDefault();
  }


  //filter search button
  $(document).ready(function(){
    $("#inlineFormInputName").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tData tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
   //filter search button
   $(document).ready(function(){
    $("#searchVisitList").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tData1 tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });


  //filter date range SHOW TABLE 
  $(document).ready(function() {
    var today = new Date();
    var fromDate = today.toISOString().split('T')[0];
    $("#fromDate").val(fromDate);
    $("#toDate").val(fromDate);
    
    filterTableByDate();

    $("#fromDate").change(function() {
        filterTableByDate();
    });

    $("#toDate").change(function() {
        filterTableByDate();
    });

    function filterTableByDate() {
        var fromDate = new Date($("#fromDate").val());
        var toDate = new Date($("#toDate").val());
        

        // Iterate through table rows and hide/show based on date range
        $("#tData tr").each(function() {
            var dateVisit = $(this).find("td:nth-child(6)").text();

            // Convert selected dates to words
            function convertToISODate(dateString) {
              const months = {
                  January: '01', February: '02', March: '03', April: '04',
                  May: '05', June: '06', July: '07', August: '08',
                  September: '09', October: '10', November: '11', December: '12'
              };
          
              const [month, day, year] = dateString.match(/(\w+) (\d+), (\d+)/).slice(1);
              const monthNumber = months[month];
              const formattedDay = day < 10 ? '0' + day : day;
          
              return `${year}-${monthNumber}-${formattedDay}`;
            }
            const originalDate = dateVisit;
            const dateVisit1 = convertToISODate(originalDate);

            // Convert the dateVisit in the table to a JavaScript Date object
            var dateVisitObj = new Date(dateVisit1);

            if (dateVisitObj >= fromDate && dateVisitObj <= toDate) {
                // Show the row if it's within the selected date range
                $(this).show();
                
                console.log(dateVisitObj);
            } else {
                // Hide the row if it's outside the selected date range
                $(this).hide();
            }
        });
    }
});

// Listen for click on toggle checkbox
function toggle(source) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i] != source)
          checkboxes[i].checked = source.checked;
  }
}
$(document).ready(function () {
  $('#dtHorizontalVerticalExample').DataTable({
    "scrollX": true,
    "scrollY": 200,
  });
  $('.dataTables_length').addClass('bs-select');
});

const displayTime = document.querySelector(".display-time");
// Time
function showTime() {
  let time = new Date();
  displayTime.innerText = time.toLocaleTimeString("en-US", { hour12: false });
  setTimeout(showTime, 1000);
}


showTime();

// Date
function updateDate() {
  let today = new Date();

  // return number
  let dayName = today.getDay(),
    dayNum = today.getDate(),
    month = today.getMonth(),
    year = today.getFullYear();

  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];
  const dayWeek = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
  ];
  // value -> ID of the html element
  const IDCollection = ["day", "month", "daynum", "year"];
  // return value array with number as a index
  const val = [dayWeek[dayName],months[month], dayNum, year];
  for (let i = 0; i < IDCollection.length; i++) {
    document.getElementById(IDCollection[i]).firstChild.nodeValue = val[i];
  }
}

updateDate();

function rgbToHex(rgb) {
    // Extract the RGB values using a regular expression
    var rgbArray = rgb.match(/\d+/g);

    // Convert each RGB component to hexadecimal
    var hex = rgbArray.map(function (value) {
        var hexValue = parseInt(value).toString(16);
        return hexValue.length === 1 ? "0" + hexValue : hexValue; // Ensure two digits
    });

    // Return the hexadecimal color
    return "#" + hex.join("");
}


function updateThemeColor(color, type) {
    // Convert RGB to Hex if color is in RGB format
    var hexColor = color.startsWith("rgb") ? rgbToHex(color) : color;

    // Make an AJAX request to update the theme color in the database
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "js/update_theme_color.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the color value and type to the server
    xhr.send("color=" + encodeURIComponent(hexColor) + "&type=" + encodeURIComponent(type));
}
