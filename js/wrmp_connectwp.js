$j=jQuery.noConflict();

var delEvent=false;

function testSite(id) {
 
	if  (document.getElementById("wrmp_host_"+id) && document.getElementById("wrmp_username_"+id) && document.getElementById("wrmp_pwd_"+id)) {
   loadP();
		var h = document.getElementById("wrmp_host_"+id).value; 
		var u = document.getElementById("wrmp_username_"+id).value; 
		var p = document.getElementById("wrmp_pwd_"+id).value; 
		if (h!="" && u!="" && p!="" && testWp(h,u,p)) {
			alert("Connection to "+h+" is ok");
			  $j('#overlay').remove();
			return true;
		} else {
			alert("Error Connection to "+h);
			  $j('#overlay').remove();
			return false;
		}
	}	
	 
	return false;
}
function testWp(host,u,p) {
	 var connection = {
		url : host+"/xmlrpc.php",
		username : u,
		password : p 
	};
	 
	var wp = new WordPress(connection.url, connection.username, connection.password);
 	var blogId = 1;
	var postId = 1;
	var object = wp.getPost(blogId, postId);
    if (object.faultString) {
		 
		return false;
	} else {
		return true;
	}
 	return false;
}

function getWpPost(h,u,p,idPost) {
	 var connection = {
		url : h+"/xmlrpc.php",
		username : u,
		password : p 
	};
	 
	var wp = new WordPress(connection.url, connection.username, connection.password);
 	var blogId = 1;
	var postId = idPost;
	var object = wp.getPost(blogId, postId);
    if (object.faultString) {
		 alert(object.faultString);
		return false;
	} else {
		return true;
	}
 	return false;
}

function fileExists(url) {
    if(url){
        var req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.send();
        return req.status==200;
    } else {
        return false;
    }
}
	function wrmpvalidateForm() { 
 	 
 	 
		 
		 	var rs = false;
		 if (delEvent==true) {
		 rs=true;
		 	 
		 } else {
		 	 
		 		loadP();
			  $j('.required').each(function() {
			   
				if ($j(this).val() == '') { 
				  $j(this).addClass('highlight');
				}
			  });

			  if ($j('.required').hasClass('highlight')) {
					alert("Please fill all fields");
				  $j('#overlay').remove();
				rs= false;
			  } else {
			  	var h = document.getElementById("wrmp_host_111111").value;
			  	if (h!="" && fileExists(h)) {
			  		 rs = testSite(111111) ;
			  			 
			  	} else {
			  		alert("Error: not valid host");
			  		  $j('#overlay').remove();
					rs= false;
			  	} 
			  	
			  
			  }
			 } 
		
		if (rs==true) {
		  
		  	document.forms["wrmpform"].submit();
			 
			    
			   	} 
		 
			  
			    
			   	return;
	} 
	
	
 

    function loadP() {
     
    
        // add the overlay with loading image to the page
        var over = '<div id="overlay">' +
            '<img id="loading" src="'+document.getElementById("wrmp_plugin_path").value+'">' +
            '</div>';
        $j(over).appendTo('body');

        // click on the overlay to remove it
        //$j('#overlay').click(function() {
        //    $j(this).remove();
        //});

        // hit escape to close the overlay
        $j(document).keyup(function(e) {
            if (e.which === 27) {
                $j('#overlay').remove();
            }
        });
        
	 

    }
 
 