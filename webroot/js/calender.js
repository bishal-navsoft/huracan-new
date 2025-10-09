var datePickerDivID = "datepicker";
var iFrameDivID = "datepickeriframe";

var dayArrayShort = new Array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
 

var defaultDateSeparator = "-";        // common values would be "/" or "."
var defaultDateFormat = "Ymd"    // valid values are "mdy", "dmy", and "ymd"
var dateSeparator = defaultDateSeparator;
var dateFormat = defaultDateFormat;


function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)
{
  if(dateFieldName=='end_job'){
    var start_job_val=trim(document.getElementById('start_job').value);
    var end_job_val=trim(document.getElementById('end_job').value);
    if(start_job_val==''){
      document.getElementById('start_job_error').innerHTML='Please select start job';
      document.getElementById('start_job').focus();
      document.getElementById('start_job').value='';
      return false;
    }else{
      document.getElementById('start_job_error').innerHTML=' ';
    }
  }	
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
  // if we weren't told what node to display the datepicker beneath, just display it
  // beneath the date field we're updating
  if (!displayBelowThisObject)
    displayBelowThisObject = targetDateField;
  // if a date separator character was given, update the dateSeparator variable
  if (dtSep)
    dateSeparator = dtSep;
  else
    dateSeparator = defaultDateSeparator;
  // if a date format was given, update the dateFormat variable
  if (dtFormat)
    dateFormat = dtFormat;
  else
    dateFormat = defaultDateFormat;
 
  var x = displayBelowThisObject.offsetLeft;
  var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
 
  // deal with elements inside tables and such
  var parent = displayBelowThisObject;
  while (parent.offsetParent) {
    parent = parent.offsetParent;
    x += parent.offsetLeft;
    y += parent.offsetTop ;
  }
 
  drawDatePicker(targetDateField, x, y);
}

/*function drawDatePicker(targetDateField, x, y)
{
  var dt = getFieldDate(targetDateField.value );
 
  // the datepicker table will be drawn inside of a <div> with an ID defined by the
  // global datePickerDivID variable. If such a div doesn't yet exist on the HTML
  // document we're working with, add one.
  if (!document.getElementById(datePickerDivID)) {
    // don't use innerHTML to update the body, because it can cause global variables
    // that are currently pointing to objects on the page to have bad references
    //document.body.innerHTML += "<div id='" + datePickerDivID + "' class='dpDiv'></div>";
    var newNode = document.createElement("div");
    newNode.setAttribute("id", datePickerDivID);
    newNode.setAttribute("class", "dpDiv");
    newNode.setAttribute("style", "visibility: hidden;");
    document.body.appendChild(newNode);
  }*/
function drawDatePicker(targetDateField, x, y) {
  var dt = getFieldDate(targetDateField.value);
 
  // the datepicker table will be drawn inside of a <div> with an ID defined by the
  // global datePickerDivID variable. If such a div doesn't yet exist on the HTML
  // document we're working with, add one.
  if (!document.getElementById(datePickerDivID)) {
    // don't use innerHTML to update the body, because it can cause global variables
    // that are currently pointing to objects on the page to have bad references
    var newNode = document.createElement("div");
    newNode.setAttribute("id", datePickerDivID);
    newNode.setAttribute("class", "dpDiv");
    newNode.setAttribute("style", "visibility: hidden;");
    document.body.appendChild(newNode);
  }
  // move the datepicker div to the proper x,y coordinate and toggle the visiblity
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.position = "absolute";
  pickerDiv.style.left = x + "px";
  pickerDiv.style.top = y + "px";
  pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");
  pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");
  pickerDiv.style.zIndex = 10000;
 
  // draw the datepicker table
  refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
  
  // Modified on April 28,2009 - Departure Calendar should show the dates according to arrival
  if (targetDateField.name == 'Departure_Date') {
    var arrivalField = document.getElementById('Arrival_Date');
    if (arrivalField && arrivalField.value.trim() !== '') {
      var arrDate = getFieldDate(arrivalField.value);
      refreshDatePicker(targetDateField.name, arrDate.getFullYear(), arrDate.getMonth(), arrDate.getDate() + 1);
    }
  }
  //End of Modification
  
}

/**
This is the function that actually draws the datepicker calendar.
*/
// function refreshDatePicker(dateFieldName, year, month, day)
// {
//   // if no arguments are passed, use today's date; otherwise, month and year
//   // are required (if a day is passed, it will be highlighted later)
//   var thisDay = new Date();
 
//   if ((month >= 0) && (year > 0)) {
//     thisDay = new Date(year, month, 1);
//   } else {
//     day = thisDay.getDate();
//     thisDay.setDate(1);
//   }
 
//   // the calendar will be drawn as a table
//   // you can customize the table elements with a global CSS style sheet,
//   // or by hardcoding style and formatting elements below
//   var crlf = "\r\n";
//   var TABLE = "<table cols=7 class='dpTable'>" + crlf;
//   var xTABLE = "</table>" + crlf;
//   var TR = "<tr class='dpTR'>";
//   var TR_title = "<tr class='dpTitleTR'>";
//   var TR_days = "<tr class='dpDayTR'>";
//   var TR_todaybutton = "<tr class='dpTodayButtonTR'>";
//   var xTR = "</tr>" + crlf;
//   var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
//   var TD_title = "<td colspan=5 class='dpTitleTD'>";
//   var TD_buttons = "<td class='dpButtonTD'>";
//   var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";
//   var TD_days = "<td class='dpDayTD'>";
//   var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
//   var xTD = "</td>" + crlf;
//   var DIV_title = "<div class='dpTitleText'>";
//   var DIV_selected = "<div class='dpDayHighlight'>";
//   var xDIV = "</div>";
 
//   // start generating the code for the calendar table
//   var html = TABLE;
 
//   // this is the title bar, which displays the month and the buttons to
//   // go back to a previous month or forward to the next month
//   html += TR_title;
//   html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;
//   html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;
//   html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;
//   html += xTR;
 
//   // this is the row that indicates which day of the week we're on
//   html += TR_days;
//   for(i = 0; i < dayArrayShort.length; i++)
//     html += TD_days + dayArrayShort[i] + xTD;
//   html += xTR;
 
//   // now we'll start populating the table with days of the month
//   html += TR;
 
//   // first, the leading blanks
//   for (i = 0; i < thisDay.getDay(); i++)
//     html += TD + "&nbsp;" + xTD;
 
//   // now, the days of the month
//   do {
//     dayNum = thisDay.getDate();
//     TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";
// 	var tday = new Date();
// 	var cday = new Date(tday.getFullYear(),tday.getMonth(),tday.getDate());
// 	var maxday = new Date(tday.getFullYear()+1,tday.getMonth(),tday.getDate());
// 	if(thisDay>=cday && thisDay<=maxday){
//         if (dayNum == day)
//              html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
//         else
//              html += TD + TD_onclick + dayNum + xTD;
// 	}else{
// 	 /*if (dayNum == day)
//                html += TD_selected + ">" + DIV_selected + "<strike>"+dayNum+"</strike>" + xDIV + xTD;
//            else
//                html += "<td>" + "<strike><font color='white'>"+dayNum+"</strike></font>" + xTD;
//        */
		
// 	 html += TD + TD_onclick + dayNum + xTD;

// 	}
//     // if this is a Saturday, start a new row
//     if (thisDay.getDay() == 6)
//       html += xTR + TR;
    
//     // increment the day
//     thisDay.setDate(thisDay.getDate() + 1);
//   } while (thisDay.getDate() > 1)
 
//   // fill in any trailing blanks
//   if (thisDay.getDay() > 0) {
//     for (i = 6; i > thisDay.getDay(); i--)
//       html += TD + "&nbsp;" + xTD;
//   }
//   html += xTR;
 
//   // add a button to allow the user to easily return to today, or close the calendar
//   var today = new Date();
//   var todayString = "Today is " + dayArrayMed[today.getDay()] + ", " + monthArrayMed[ today.getMonth()] + " " + today.getDate();
//   html += TR_todaybutton + TD_todaybutton;
//   html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>this month</button> ";
//   html += "<button class='dpTodayButton' onClick='updateDateField(\"" + dateFieldName + "\");'>close</button>";
//   html += xTD + xTR;
 
//   // and finally, close the table
//   html += xTABLE;
 
//   document.getElementById(datePickerDivID).innerHTML = html;
//   // add an "iFrame shim" to allow the datepicker to display above selection lists
//   adjustiFrame();
// }
function refreshDatePicker(dateFieldName, year, month, day) {
  // --- normalize incoming params to numbers (important!) ---
  year = Number(year);
  month = Number(month);
  day = (typeof day === 'undefined' || day === null) ? null : Number(day);

  const today = new Date();

  // fallbacks if values were invalid
  if (!Number.isFinite(year)) year = today.getFullYear();
  if (!Number.isFinite(month)) month = today.getMonth();
  if (!Number.isFinite(day)) day = today.getDate();

  // Normalize month/year using Date (handles month <0 or >11)
  const thisDay = new Date(year, month, 1);
  const displayYear = thisDay.getFullYear();
  const displayMonth = thisDay.getMonth(); // 0..11

  // --- build month options (use displayMonth for selection) ---
  const monthOptions = monthArrayLong
    .map((m, i) => `<option value="${i}" ${i === displayMonth ? 'selected' : ''}>${m}</option>`)
    .join('');

  // --- build year options (choose a sensible range) ---
  const yearStart = today.getFullYear() - 50;
  const yearEnd = today.getFullYear() + 10;
  let yearOptions = "";
  for (let y = yearStart; y <= yearEnd; y++) {
    yearOptions += `<option value="${y}" ${y === displayYear ? 'selected' : ''}>${y}</option>`;
  }

  // prev/next month normalized
  const prev = new Date(displayYear, displayMonth - 1, 1);
  const next = new Date(displayYear, displayMonth + 1, 1);

  // --- build HTML (month/year selects use normalized values) ---
  let html = `
    <table class="dpTable">
      <tr class="dpTitleTR">
        <td class="dpButtonTD">
          <button class="dpButton" onclick="refreshDatePicker('${dateFieldName}', ${prev.getFullYear()}, ${prev.getMonth()}, ${day})">&lt;</button>
        </td>
        <td colspan="5" class="dpTitleTD" style="text-align:center;">
          <select class="dpMonthSelect" onchange="refreshDatePicker('${dateFieldName}', ${displayYear}, this.value, ${day})">
            ${monthOptions}
          </select>
          <select class="dpYearSelect" onchange="refreshDatePicker('${dateFieldName}', this.value, ${displayMonth}, ${day})">
            ${yearOptions}
          </select>
        </td>
        <td class="dpButtonTD">
          <button class="dpButton" onclick="refreshDatePicker('${dateFieldName}', ${next.getFullYear()}, ${next.getMonth()}, ${day})">&gt;</button>
        </td>
      </tr>
      <tr class="dpDayTR">
        ${dayArrayShort.map(d => `<td class='dpDayTD'>${d}</td>`).join('')}
      </tr>
  `;

  // leading blanks
  html += "<tr>";
  for (let i = 0; i < thisDay.getDay(); i++) html += "<td>&nbsp;</td>";

  // fill the days
  const loopDay = new Date(thisDay);
  do {
    const dayNum = loopDay.getDate();
    const isToday = loopDay.toDateString() === today.toDateString();
    const selectedClass = (dayNum === day) ? "dpDayHighlightTD" : "dpTD";
    html += `<td class='${selectedClass}' onclick="updateDateField('${dateFieldName}', '${getDateString(loopDay)}')">${isToday ? "<b>" + dayNum + "</b>" : dayNum}</td>`;

    if (loopDay.getDay() === 6) html += "</tr><tr>";
    loopDay.setDate(loopDay.getDate() + 1);
  } while (loopDay.getDate() > 1);

  // if trailing blanks needed
  if (loopDay.getDay() > 0) {
    for (let i = 6; i > loopDay.getDay(); i--) html += "<td>&nbsp;</td>";
  }
  html += `
      </tr>
      <tr class="dpTodayButtonTR">
        <td colspan="7" class="dpTodayButtonTD">
          <button class="dpTodayButton" onclick="refreshDatePicker('${dateFieldName}', ${today.getFullYear()}, ${today.getMonth()}, ${today.getDate()})">Today</button>
          <button class="dpTodayButton" onclick="updateDateField('${dateFieldName}')">Close</button>
        </td>
      </tr>
    </table>
  `;

  document.getElementById(datePickerDivID).innerHTML = html;
  adjustiFrame();

  // --- optional: attach JS listeners instead of inline attributes (safer)
  // (re-attach after each render because innerHTML was replaced)
  const container = document.getElementById(datePickerDivID);
  const monthSelect = container.querySelector('.dpMonthSelect');
  const yearSelect = container.querySelector('.dpYearSelect');
  if (monthSelect) {
    monthSelect.addEventListener('change', (e) => {
      const selectedMonth = Number(e.target.value);
      refreshDatePicker(dateFieldName, displayYear, selectedMonth, day);
    });
  }
  if (yearSelect) {
    yearSelect.addEventListener('change', (e) => {
      const selectedYear = Number(e.target.value);
      refreshDatePicker(dateFieldName, selectedYear, displayMonth, day);
    });
  }
}



function getButtonCode(dateFieldName, dateVal, adjust, label)
{
  var newMonth = (dateVal.getMonth () + adjust) % 12;
  var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
  if (newMonth < 0) {
    newMonth += 12;
    newYear += -1;
  }
 
  return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";
}

/*function getDateString(dateVal)
{
  var dayString = "00" + dateVal.getDate();
  var monthString = "00" + (dateVal.getMonth()+1);
  dayString = dayString.substring(dayString.length - 2);
  monthString = monthString.substring(monthString.length - 2);
 
  switch (dateFormat) {
    case "dmy" :
      return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
    case "ymd" :
      return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
    case "mdy" :
    default :
      return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
  }
}*/
function getDateString(dateVal) {
  var dayString = "00" + dateVal.getDate();
  var monthString = "00" + (dateVal.getMonth() + 1);
  dayString = dayString.substring(dayString.length - 2);
  monthString = monthString.substring(monthString.length - 2);
  
  // Always return dd-mm-yyyy format
  return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
}

/**
Convert a string to a JavaScript Date object.
*/

/*function getFieldDate(dateString)
{
  var dateVal;
  var dArray;
  var d, m, y;
 
  try {
    dArray = splitDateString(dateString);
    if (dArray) {
      switch (dateFormat) {
        case "dmy" :
          d = parseInt(dArray[0], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
        case "ymd" :
          d = parseInt(dArray[2], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[0], 10);
          break;
        case "mdy" :
        default :
          d = parseInt(dArray[1], 10);
          m = parseInt(dArray[0], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
      }
      dateVal = new Date(y, m, d);
    } else if (dateString) {
      dateVal = new Date(dateString);
    } else {
      dateVal = new Date();
    }
  } catch(e) {
    dateVal = new Date();
  }
 
  return dateVal;
}*/
function getFieldDate(dateString) {
  var dateVal;
  var dArray;
  var d, m, y;
 
  try {
    dArray = splitDateString(dateString);
    if (dArray) {
      // Always use dmy format (dd-mm-yyyy)
      d = parseInt(dArray[0], 10);
      m = parseInt(dArray[1], 10) - 1;
      y = parseInt(dArray[2], 10);
      
      // Validate the parsed values
      if (isNaN(d) || isNaN(m) || isNaN(y)) {
        dateVal = new Date();
      } else {
        dateVal = new Date(y, m, d);
        // Check if the date is valid
        if (dateVal.getFullYear() !== y || dateVal.getMonth() !== m || dateVal.getDate() !== d) {
          dateVal = new Date();
        }
      }
    } else if (dateString && dateString.trim() !== '') {
      dateVal = new Date(dateString);
      // If invalid date, use today
      if (isNaN(dateVal.getTime())) {
        dateVal = new Date();
      }
    } else {
      dateVal = new Date();
    }
  } catch(e) {
    dateVal = new Date();
  }
 
  return dateVal;
}

/*function splitDateString(dateString)
{
  var dArray;
  if (dateString.indexOf("/") >= 0)
    dArray = dateString.split("/");
  else if (dateString.indexOf(".") >= 0)
    dArray = dateString.split(".");
  else if (dateString.indexOf("-") >= 0)
    dArray = dateString.split("-");
  else if (dateString.indexOf("\\") >= 0)
    dArray = dateString.split("\\");
  else
    dArray = false;
 
  return dArray;
}*/
function splitDateString(dateString) {
  if (!dateString || dateString.trim() === '') {
    return null;
  }
  
  var separator = dateSeparator || '-';
  var parts = dateString.trim().split(separator);
  
  if (parts.length === 3) {
    return parts;
  }
  
  return null;
}

function updateDateField(dateFieldName, dateString)
{

  var targetDateField = document.getElementsByName (dateFieldName).item(0);

  if (dateString){
    targetDateField.value = dateString;
  
    
  
      if(dateFieldName=='event_date'){
	       document.getElementById('event_date_error').innerHTML=' ';
	       check_diff(dateString);
	 }
        if(dateFieldName=='end_job'){
	     endStartDiff();
	 }
	if(dateFieldName=='start_job'){
	       var end_job_val=trim(document.getElementById('end_job').value);
	       if(end_job_val!=''){
		   callEndStartDiff();
	         }	      
	 }
  
   }
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.visibility = "hidden";
  pickerDiv.style.display = "none";
 
  adjustiFrame();
  targetDateField.focus();
 

  if ((dateString) && (typeof(datePickerClosed) == "function"))
    datePickerClosed(targetDateField);
}


function adjustiFrame(pickerDiv, iFrameDiv)
{
  // we know that Opera doesn't like something about this, so if we
  // think we're using Opera, don't even try
  var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);
  if (is_opera)
    return;
  
  // put a try/catch block around the whole thing, just in case
  try {
    if (!document.getElementById(iFrameDivID)) {
      // don't use innerHTML to update the body, because it can cause global variables
      // that are currently pointing to objects on the page to have bad references
      //document.body.innerHTML += "<iframe id='" + iFrameDivID + "' src='javascript:false;' scrolling='no' frameborder='0'>";
      var newNode = document.createElement("iFrame");
      newNode.setAttribute("id", iFrameDivID);
      newNode.setAttribute("src", "javascript:false;");
      newNode.setAttribute("scrolling", "no");
      newNode.setAttribute ("frameborder", "0");
      document.body.appendChild(newNode);
    }
    
    if (!pickerDiv)
      pickerDiv = document.getElementById(datePickerDivID);
    if (!iFrameDiv)
      iFrameDiv = document.getElementById(iFrameDivID);
    
    try {
      iFrameDiv.style.position = "absolute";
      iFrameDiv.style.width = pickerDiv.offsetWidth;
      iFrameDiv.style.height = pickerDiv.offsetHeight ;
      iFrameDiv.style.top = pickerDiv.style.top;
      iFrameDiv.style.left = pickerDiv.style.left;
      iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;
      iFrameDiv.style.visibility = pickerDiv.style.visibility ;
      iFrameDiv.style.display = pickerDiv.style.display;
    } catch(e) {
    }
 
  } catch (ee) {
  }
 
}

