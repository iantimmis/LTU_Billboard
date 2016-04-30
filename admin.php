<?php
    include 'upcomingEvents.php';
?>

<!DOCTYPE html>
<html>
<head>
  <link href="fcStylesheet.css" rel="stylesheet" type="text/css" />
  <link href="adminStylesheet.css" rel="stylesheet" type="text/css" />
  <link href="adminStylesheet.css" rel="stylesheet" type="text/css" />
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <script>
      var currPage=0
      var maxPage=0
      var sortBy=0
      var type=0
      function onLoad()
      {
          baseFunction("http://localhost/ltu_billboard/findMax.php", findMaxPage);
          baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests);
      }
      function next()
      {
          if(currPage==maxPage)
          {
              //do nothing
          }
          else
          {
              currPage++;
              baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }
      function prev()
      {
          if(currPage==0)
          {
              //do nothing
          }
          else
          {
              currPage--;
              baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }
      function sortByRequestDate()
      {
          if(sortBy==0)
          {
              //do nothing
          }
          else
          {
              sortBy=0;

              document.getElementById("sortDate").style.backgroundColor="rgba(255, 255, 255, .3)";
              document.getElementById("sortDate").style.color="#003d78";
              document.getElementById("sortRecent").style.backgroundColor="#3385ff";
              document.getElementById("sortRecent").style.color="white";

              baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
        
      }
      function sortByEventDate()
      {
          if(sortBy==1)
          {
              //do nothing
          }
          else
          {
              sortBy=1;

              document.getElementById("sortRecent").style.backgroundColor="rgba(255, 255, 255, .3)";
              document.getElementById("sortRecent").style.color="#003d78";
              document.getElementById("sortDate").style.backgroundColor="#3385ff";
              document.getElementById("sortDate").style.color="white";

              baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }
      function showEventRequests()
      {
          if(type==0)
          {
              //do nothing
          }
          else
          {
              type=0;

              document.getElementById("orgReq").style.backgroundColor="rgba(255, 255, 255, .3)";
              document.getElementById("orgReq").style.color="#003d78";
              document.getElementById("eventReq").style.backgroundColor="#3385ff";
              document.getElementById("eventReq").style.color="white";

              
              document.getElementById("sortDate").style.backgroundColor="rgba(255, 255, 255, .3)";
              document.getElementById("sortDate").style.color="#003d78";
              document.getElementById("sortDate").style.display="initial";
              document.getElementById("sortRecent").style.backgroundColor="#3385ff";
              document.getElementById("sortRecent").style.color="white";
              

              baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }
      function showOrgRequests()
      {
          if(type==1)
          {
              //do nothing
          }
          else
          {
              type=1;

              document.getElementById("eventReq").style.backgroundColor="rgba(255, 255, 255, .3)";
              document.getElementById("eventReq").style.color="#003d78";
              document.getElementById("orgReq").style.backgroundColor="#3385ff";
              document.getElementById("orgReq").style.color="white";

              document.getElementById("sortDate").style.display="none";
              document.getElementById("sortRecent").style.backgroundColor="#3385ff";
              document.getElementById("sortRecent").style.color="white";

              baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }
      function baseFunction(url, nameOfFunction)
      {
          var xhttp=new XMLHttpRequest();
          xhttp.onreadystatechange=
              function()
              {
                  if(xhttp.readyState==4 && xhttp.status==200)
                  {
                      nameOfFunction(xhttp);
                  }
              };
              xhttp.open("GET", url, true);
              xhttp.send();
      }
      function baseFunctionPost(url, nameOfFunction, msg)
      {
          var xhttp=new XMLHttpRequest();
          xhttp.onreadystatechange=
              function()
              {
                  if(xhttp.readyState==4 && xhttp.status==200)
                  {
                      nameOfFunction(xhttp);
                  }
              };
              xhttp.open("POST", url, true);
              xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              xhttp.send(msg);
      }
      function findMaxPage(xhttp)
      {
          maxPage=xhttp.responseText;   
      }
      function showRequests(xhttp)
      {
          document.getElementById("requestTable").innerHTML=xhttp.responseText;        
      }
      function handleAcceptance(xhttp)
      {
          document.getElementById("acceptanceResponse").innerHTML=xhttp.responseText;
      }
      function accept(acc)
      {
          var arr=document.getElementsByTagName("INPUT");  
          var checkedIt=new Array();
          for(var i=0; i<arr.length; i++)
          {
              if(arr[i].className=="checks" && arr[i].checked)
              {
                  var rowID=arr[i].id;
                  checkedIt.push(rowID);
              }
          }
          if(checkedIt==="undefined" || checkedIt.length==0)
          {
              return;
          }
          if(acc==0)
          {
              var decision=confirm("Are you sure you want to delete request(s)?");
              if(!decision)
              {
	          return;
              }	
          }
          else
          {
              var decision=confirm("Are you sure you want to accpet request(s)?");
              if(!decision)
              {
	          return;
              }	
          }
          var checkedItems=new Array();
          for(var j=0; j<checkedIt.length; j++)
          {
              /*var val=0;*/
              var val=document.getElementById("reqs").rows[checkedIt[j]].cells[0].innerHTML;
              checkedItems.push(val);
          }
          var data="a="+acc+"&type="+type+"&data="+checkedItems;
          baseFunctionPost("http://localhost/ltu_billboard/acceptOrDecline.php", handleAcceptance, data); 
          baseFunction("http://localhost/ltu_billboard/buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests); 
      }
      function changeAll()
      {
          var arr=document.getElementsByTagName("INPUT");       
          if(document.getElementById("masterCheck").checked)  
          {			
              for(var i=0; i<arr.length; i++)
              {
                  if(arr[i].className=="checks" && !arr[i].checked)
                  {
                      arr[i].checked=true;
                  }
              }
          }
          else 
          {
              for(var i=0; i<arr.length; i++)
              {
                  if(arr[i].className=="checks" && arr[i].checked)
                  {
                      arr[i].checked=false;
                  }
              }
          }
      }
</script>
</head>
<body onload="onLoad()">
  <header>
    <a href="create_events_page.htm"><button class="event">Create Event</button></a>
    <a href="fcIndex.html" id=logo>LTU Billboard</a>
    <span class="log-in">
      <a href=""><button id="loginButton">Log In</button></a>
      <br/>
      Not a user? <a href="">Join Now</a>
    </span>
    <br/>
    <br/>
    <br/>
  </header>
  <div id="mainWrap">
    <div id="title">Administrator</div>
    <div id="acceptanceResponse"></div>
    <div id="type">Current Requests</div>
    <div id="requests">
      <div id="requestHeader">
          <div id="decision">
          <button class="choose" onclick="accept(1)">Accept</button>
          or 
          <button class="choose" onclick="accept(0)">Decline</button>
          requests.
        </div>
        <div id="sorting">
          <span id="sortType">Sort By:</span>
          <button class="sort" id="sortRecent" onclick="sortByRequestDate()">Recently Requested</button>
          <button class="sort" id="sortDate" onclick="sortByEventDate()">Event Date</button> 
        </div> 
        <div id-"type">
          <button class="requestType" id="eventReq" onclick="showEventRequests()">Events</button>
          <button class="requestType" id="orgReq" onclick="showOrgRequests()">Organizations</button>
          <span id="checkText">Select/Clear All: <input id="masterCheck" type="checkbox" onchange="changeAll()" /></span>
        </div>
      </div>
      <div id="requestTable">
      </div>
      <div id="navbar">
       <button class="navButton" id="prev" onclick="prev()">Prev</button>
       <button class="navButton" id="next" onclick="next()">Next</button>
      </div>
    </div>
    <div id="sideBar">
      <div id="searchBox">
        <p><h3>Search for an event:</h3></p>
        <input type="text"></input>
        <button>Search</button>
        <br/><br/>
      </div>
      <div id="links">
        <a href="fcIndex.html" class="link">View Calendar</a><br/>
        <a href="" class="link">View Organizations</a><br/>
        <a href="" class="link">Edit Annoucements</a>
      </div>
      <br/>
      <hr/>
      <div id="eventsThisMonth">
        <h3>Upcoming Events</h3>
        <ul id="theList"><?php upcomingEvents() ?></ul>
      </div>
    </div>
  </div> 
  <div id="bottomWrapper">
    <div id="testArea"></div>
    <footer>
      Created By: Matthew Castaldini, Hanan Jalnko, Kathleen Napier, Ian Tammis
    </footer> 
  </div>
</body>
</html>