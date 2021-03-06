//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due. 
//
// Author: Paul Lemmons 
//----------------------------------------------------------------------------

// These variables are for saving the original background colors
var savedStates=new Array();
var savedStateCount=0;

/////////////////////////////////////////////////////
// This function takes an element as a parameter and
//   returns an object which contain the saved state
//   of the element's background color.
/////////////////////////////////////////////////////
function saveBackgroundStyle(myElement)
{
  saved=new Object();
  saved.element=myElement;
  saved.className=myElement.className;
  saved.backgroundColor=myElement.style["backgroundColor"];
  return saved;
}

/////////////////////////////////////////////////////
// This function takes an element as a parameter and
//   returns an object which contain the saved state
//   of the element's background color.
/////////////////////////////////////////////////////
function restoreBackgroundStyle(savedState)
{
  savedState.element.style["backgroundColor"]=savedState.backgroundColor;
  if (savedState.className)
  {
    savedState.element.className=savedState.className;
  }
}

/////////////////////////////////////////////////////
// This function is used by highlightTableRow() to find table cells (TD) node
/////////////////////////////////////////////////////
function findNode(startingNode, tagName)
{
  // on Firefox, the TD node might not be the firstChild node of the TR node
  myElement=startingNode;
  var i=0;
  while (myElement && (!myElement.tagName || (myElement.tagName && myElement.tagName!=tagName)))
  {
    myElement=startingNode.childNodes[i];
    i++;
  }
  if (myElement && myElement.tagName && myElement.tagName==tagName)
  {
    return myElement;
  }
  // on IE, the TD node might be the firstChild node of the TR node
  else if (startingNode.firstChild)
    return findNode(startingNode.firstChild, tagName);
  return 0;
}

/////////////////////////////////////////////////////
// Highlight table row.
// newElement could be any element nested inside the table
// highlightColor is the color of the highlight
/////////////////////////////////////////////////////
function highlightTableRow(myElement, highlightColor)
{
  var i=0;
  // Restore color of the previously highlighted row
  for (i; i<savedStateCount; i++)
  {
    restoreBackgroundStyle(savedStates[i]);
  }
  savedStateCount=0;

  // To get the node to the row (ie: the <TR> element),
  // we need to traverse the parent nodes until we get a row element (TR)
  // Netscape has a weird node (if the mouse is over a text object, then there's no tagName
  while (myElement && ((myElement.tagName && myElement.tagName!="TR") || !myElement.tagName))
  {
    myElement=myElement.parentNode;
  }

  // If you don't want a particular row to be highlighted, set it's id to "header"
  // If you don't want a particular row to be highlighted, set it's id to "header"
  if (!myElement || (myElement && myElement.id && myElement.id=="header") )
    return;

  // Highlight every cell on the row
  if (myElement)
  {
    var tableRow=myElement;

    // Save the backgroundColor style OR the style class of the row (if defined)
    if (tableRow)
    {
     savedStates[savedStateCount]=saveBackgroundStyle(tableRow);
      savedStateCount++;
    }

    // myElement is a <TR>, then find the first TD
    var tableCell=findNode(myElement, "TD");

    var i=0;
    // Loop through every sibling (a sibling of a cell should be a cell)
    // We then highlight every siblings
    while (tableCell)
    {
      // Make sure it's actually a cell (a TD)
      if (tableCell.tagName=="TD" && tableCell.id != 'preserve')
      {
        // If no style has been assigned, assign it, otherwise Netscape will
        // behave weird.
        if (!tableCell.style)
        {
          tableCell.style={};
        }
        else
        {
          savedStates[savedStateCount]=saveBackgroundStyle(tableCell);
          savedStateCount++;
        }
        // Assign the highlight color
        tableCell.style["backgroundColor"]=highlightColor;

        // Optional: alter cursor
        tableCell.style.cursor='default';
        i++;
      }
      // Go to the next cell in the row
      tableCell=tableCell.nextSibling;
    }
  }
}

/////////////////////////////////////////////////////
// This function is to be assigned to a <table class='registrationTable'> mouse event handler.
// If the element that fired the event is within a table row,
//   this function will highlight the row.
/////////////////////////////////////////////////////
function trackTableHighlight(mEvent, highlightColor)
{
  if (!mEvent)
    mEvent=window.event;

  // Internet Explorer
  if (mEvent.srcElement)
  {
    highlightTableRow( mEvent.srcElement, highlightColor);
  }
  // Netscape and Firefox
  else if (mEvent.target)
  {
    highlightTableRow( mEvent.target, highlightColor);
  }
}
/////////////////////////////////////////////////////
// This function opens and closes the team list
// in the TallyAssignAwards program
/////////////////////////////////////////////////////
function show_team(TeamNumber)
{
   var Participant_index = 0;
   // Make sure the element exists before calling it's properties
   while ((document.getElementById("team_"+String(TeamNumber)+"_"+String(Participant_index)) != null))
   {
      // Toggle visibility between none and inline
      if (document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)).style.display == 'none')
      {
         document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)).style.display = 'table-row';
         document.getElementById("team_"+TeamNumber).value = '(close)';
      }
      else
      {
         document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)).style.display = 'none';
         document.getElementById("team_"+TeamNumber).value = '(open)';
      }
      Participant_index++;
   }
}
