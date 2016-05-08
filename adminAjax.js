/*All javascript variables and functions used to build admin page*/

      var currPage=0 //Current request page displayed in table
      var maxPage=0  //Maximum number of pages that can be displayed
      var sortBy=0  //Sort table by requests if (0), events if (1)
      var type=0  //Show events table if (0), organization table if (1)

      /* On-load display event requests table, sorted by most recent request */
      function onLoad()
      {
          baseFunction("findMax.php", findMaxPage);
          baseFunction("buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests);
      }

      /* Display next 20 requests in the request table */
      function next()
      {
          if(currPage==maxPage)
          {
              //do nothing
          }
          else
          {
              currPage++;
              baseFunction("buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }

      /* Display the previous 20 requests in the request table */
      function prev()
      {
          if(currPage==0)
          {
              //do nothing
          }
          else
          {
              currPage--;
              baseFunction("buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }

      /* Events or Organization table sorted by most recent request */
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

              baseFunction("buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
        
      }
  
      /* Events table sorted by proximity of event data. */
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

              baseFunction("buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }

      /* Ajax rendering of event requests table */
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
              

              baseFunction("buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }

      /* Ajax rendering of organization requests table */
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

              baseFunction("buildRequestTable.php?page="+0+"&sort="+sortBy+"&type="+type, showRequests);
          }
      }
      
      /* XMLHttpRequest GET message for ajax rendering */
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

      /* XMLHttpRequest POST message for ajax rendering */
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

      /* Function to accept or decline a request */
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
              var val=document.getElementById("reqs").rows[checkedIt[j]].cells[0].innerHTML;
              checkedItems.push(val);
          }
          var data="a="+acc+"&type="+type+"&data="+checkedItems;
          baseFunctionPost("acceptOrDecline.php", handleAcceptance, data); 
          baseFunction("buildRequestTable.php?page="+currPage+"&sort="+sortBy+"&type="+type, showRequests); 
      }

      /* Function to select or deselect all request table checkboxes */
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