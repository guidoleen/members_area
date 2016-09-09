<?php
/*
	Template Name: memberlist
*/
	// Load the Encode Decode Class
	if(!class_exists('gu_encryption'))
	{
		include plugins_url() .  '/osm_cls/cls_encrypt_decrypt.php';
	}
?>
<?php
	// instantiate class rowbuilder and build rows from class first time while loading page
	$_StrShowStart = gu_wp_show_members();
?>
<?php
	get_header();
?>
<div class="container-fluid">
  <div class="row">
    
    <!--/.Pagination -->
    <div id="swipe-element" style="margin-top:-20px;">
    <div class="pagination-mini" id="pagination">
	<ul class="pager">
	<li><a href="searchmembers?id=A&alfa=1">A</a></li>
	<li><a href="searchmembers?id=B&alfa=1">B</a></li>
	<li><a href="searchmembers?id=C&alfa=1">C</a></li>
	<li><a href="searchmembers?id=D&alfa=1">D</a></li>
	<li><a href="searchmembers?id=E&alfa=1">E</a></li>
	<li><a href="searchmembers?id=F&alfa=1">F</a></li>
	<li><a href="searchmembers?id=G&alfa=1">G</a></li>
	<li><a href="searchmembers?id=H&alfa=1">H</a></li>
	<li><a href="searchmembers?id=I&alfa=1">I</a></li>
	<li><a href="searchmembers?id=J&alfa=1">J</a></li>
	<li><a href="searchmembers?id=K&alfa=1">K</a></li>
	<li><a href="searchmembers?id=L&alfa=1">L</a></li>
	<li><a href="searchmembers?id=M&alfa=1">M</a></li>
	<li><a href="searchmembers?id=N&alfa=1">N</a></li>
	<li><a href="searchmembers?id=O&alfa=1">O</a></li>
	<li><a href="searchmembers?id=P&alfa=1">P</a></li>
	<li><a href="searchmembers?id=Q&alfa=1">Q</a></li>
	<li><a href="searchmembers?id=R&alfa=1">R</a></li>
	<li><a href="searchmembers?id=S&alfa=1">S</a></li>
	<li><a href="searchmembers?id=T&alfa=1">T</a></li>
	<li><a href="searchmembers?id=U&alfa=1">U</a></li>
	<li><a href="searchmembers?id=V&alfa=1">V</a></li>
	<li><a href="searchmembers?id=W&alfa=1">W</a></li>
	<li><a href="searchmembers?id=X&alfa=1">X</a></li>
	<li><a href="searchmembers?id=Y&alfa=1">Y</a></li>
	<li><a href="searchmembers?id=Z&alfa=1">Z</a></li>
	</ul>
	</div>
    </div>
   <span id="alfabopen" class="btn btn-default">>></span>
<script>
$(document).ready(function()
{
	var i = 0;
	$("#alfabopen").click(function()
	{
		if(i == 0)
		{
			MargLeft = '110%';
			i++;
		}
		else
		{
			MargLeft = '-100%';
			i = 0;
		}

		$("#pagination").animate(
		{
			marginLeft: MargLeft
		});
	});
});
    
</script>
    <!--/.End Pagination -->
   
   
   <!-- /////////////////////// --> 
    <!--/.Rijen Members -->
<div class="container"><!--/.RIJEN Members Start -->
    <ul class="media-list" id="userlist"><!--/.ul list -->

  <div class="row">
  	<?php echo $_StrShowStart ?>
  </div><!--/. RIJ NIEUW -->
  
   <div class="row">
  	<div id="row_nr2"></div>
  </div>
      
    </ul><!--/. END ul list -->
    <span id="loadmore" num_loaded="6" class="btn btn-default">Laat meer zien</span><br><br>
</div><!--/. END Container -->

<script>
// Load New rowresult from ajax json jquery
var newloaded = 0;
$(document).ready(function(){
    $("#loadmore").click(function(){
    	var loaded = $(this).attr('num_loaded');
    	newloaded = parseInt(newloaded) + parseInt(loaded);
	
        $.ajax({url: <?php echo "'" .  plugins_url() . '/osm_members/fct_load_data_json.php' . "'"?>,
       	 	type:'get',
       	 	// dataType: "json",
       	 	data:{'limit':loaded,'from':newloaded}, 
        	success: function(result)
        	{
        		try
        		{
            			var results = $.parseJSON(result);
            			for(i=0; i <= results.length; i++)
            			{
            				if(results[i] == null)
            				{
            					 break; 
            					 $("#loadmore").hide();
            				}
            				else
            				{
            					// new builder
            					if(results[i]['linkedin'] == "")
            					{
            						linkedin = 0;
            					}
            					else
            					{
            						linkedin = 1;
            					}
            					var Builder = rijBouwer(results[i]['id'], results[i]['username'], results[i]['last_name'], results[i]['title'], linkedin, results[i]['photo']);
            					$("#row_nr2").append(Builder);
            				}
            			}
            			// alert for testing  
            			// alert(Builder);
            		}
            		catch(e)
            		{
            			// alert('neeee');
            		}
           	}
        });
        return newloaded;
    });
}); 
</script>

<script>
////// RijBouwer ////////
var ISVIPI_UPLOADS_URL = "../wp-content/osm_uploads/";
function rijBouwer(Id, name, lastname, functie, linkdin, photo)
{
		rij =  "<div class='col-md-4'><li class='media basic_backclr' style='padding:2px;'><span class='pull-left' href=''>";
		rij += "<a href='";
		rij += "member/?memId=" + Id;
		rij += "'>";
		rij += "<img class='img-circle memimg' alt='' src='";
		
		if(photo == "")
		{
			rij += ISVIPI_UPLOADS_URL + "imgusr/usr_0.jpg'";
		}
		else
		{
			rij += ISVIPI_UPLOADS_URL + "imgusr/" + photo + "'";
		}

		rij += ">";
		rij += "</a></span><div class='media-body'><span class='text-muted pull-right'>";
	
		if(linkdin == 1)
		{
			rij +="<a href=''><i class='align-right'><img src='";
			rij += ISVIPI_UPLOADS_URL + "site/linkedin40.png'></i></a>";
		}

		rij += "</span><h3>";
		rij += name + " ";
		rij += lastname;
		rij += "</h3><p>";
        	rij += "<a href='";
        	rij += "member/?memId=" + Id;
        	rij += "'>";
        	if(functie == "")
        	{
        		 rij +=  "&nbsp";
        	}
        	else
        	{
        		rij +=  functie;
        	}
        	rij +=  "</a>";
		rij += "</p></div></li><hr></div>";

	return rij;
}

</script>
