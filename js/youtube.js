var page = 1;
var query;
function jasoncalling(query,maxresults,startindex){
   jQuery.getJSON('http://gdata.youtube.com/feeds/api/videos?q='+query+'&alt=json-in-script&callback=?&max-results='+maxresults+'&start-index='+startindex, function(data) { 
        var videoloop =0;
		var videolist =[];
		var videodesc = [];
		var titolo_desc = [];
		jQuery.each(data.feed.entry, function(i, item) {
	    var title = item['title']['$t'];
	    var video = item['id']['$t'];
		var content = item['content']['$t'];
		
	    video = video.replace('http://gdata.youtube.com/feeds/api/videos/','http://www.youtube.com/watch?v=');
	    videoID = video.replace('http://www.youtube.com/watch?v=','');
	    titolo_desc[videoloop] = title;
		videolist[videoloop] = videoID;
		videodesc[videoloop] = content;
		videoloop= videoloop+1;
		

		if(videoloop == 12)
		{
		defaultvideo(videolist,videodesc,titolo_desc)
		}
		});
	
    }); }


	
function defaultvideo(videolist,videodesc,titolo_desc){
var thevideodiv = document.getElementById('apDiv1');
var url_plugins = jQuery('#ursl_sito_js_file').val();
//thevideodiv.innerHTML = '<div id="filmato" style="z-index:10; position:relative;"><script type="text/javascript" src="'+url_plugins+'/youtubefreedown/swfobject.js"></script><div id="mediaspace"></div><script type="text/javascript">var so = new SWFObject("'+url_plugins+'/youtubefreedown/player.swf","mpl","600","337","9");so.addParam("allowfullscreen","true");so.addParam("allowscriptaccess","always");so.addParam("wmode","opaque");so.addVariable("file","http://www.youtube.com/watch?v='+videolist[0]+'");so.addVariable("stretching","fill");so.write("mediaspace");</script></div>';



jQuery('#apDiv1').html('<div id="filmato" style="z-index:10; position:relative;"><script type="text/javascript" src="'+url_plugins+'/youtubefreedown/swfobject.js"></script><div id="mediaspace"></div><script type="text/javascript">var so = new SWFObject("'+url_plugins+'/youtubefreedown/player.swf","mpl","600","337","9");so.addParam("allowfullscreen","true");so.addParam("allowscriptaccess","always");so.addParam("wmode","opaque");so.addVariable("file","http://www.youtube.com/watch?v='+videolist[0]+'");so.addVariable("stretching","fill");so.write("mediaspace");</script></div>');


jQuery('#apDiv6').hide();


jQuery('#link_down').val('http://www.youtube.com/watch?v='+videolist[0]);



//document.getElementById('apDiv1').innerHTML= videodesc[0];

//document.getElementById('apDiv6').innerHTML= videodesc[0];
//document.getElementById('apDiv6').innerHTML= 'http://www.youtube.com/watch?v='+videolist[0];

var picloop;
for(picloop=0;picloop<=11;picloop++){
var thevideopicdiv = document.getElementById('video'+picloop);
thevideopicdiv.innerHTML = '<div style="cursor:pointer;" desc="'+escape(videodesc[picloop])+'" name="http://www.youtube.com/watch?v='+videolist[picloop]+'" id="videopics"><img src="http://i.ytimg.com/vi/'+videolist[picloop]+'/default.jpg" width="110px" height="90px" id="videoslistpics" title="'+videodesc[picloop].substr(0,150)+'......" /><br />'+titolo_desc[picloop].substr(0,10)+' ...</div>';


}
return false;
}



jQuery(document).ready(function(){

jQuery('#apDiv6').hide();
jQuery('#apDiv4').hide();
jQuery('#apDiv2').hide();
jQuery('#errorbox').hide();
jQuery('#errorbox').click(function(){
jQuery(this).hide(1000);

});
jQuery('#submitsearch').click(function(){
page = 1;
query = jQuery('#searchbox').val();
if(query == '') {
jQuery('#errorbox').show(100).html('Your search query is empty !!!!').delay(2000).hide(1000);
}
else {
jQuery('#apDiv6').show();
jQuery('#apDiv4').show();
jQuery('#apDiv2').show();
jasoncalling(query,12,page) }
});




jQuery('#videopics').live("click",function(){
var videolink = jQuery(this).attr('name');
var videodesc = jQuery(this).attr('desc');
videodesc = unescape(videodesc);
var url_pluginsy = jQuery('#ursl_sito_js_file').val();
jQuery('#apDiv1').html('<div id="filmato" style="z-index:10; position:relative;"><script type="text/javascript" src="'+url_pluginsy+'/youtubefreedown/swfobject.js"></script><div id="mediaspace">This text will be replaced aaa</div><script type="text/javascript">var so = new SWFObject("'+url_pluginsy+'/youtubefreedown/player.swf","mpl","600","337","9");so.addParam("allowfullscreen","true");so.addParam("allowscriptaccess","always");so.addParam("wmode","opaque");so.addVariable("file","'+videolink+'");so.addVariable("stretching","fill");so.write("mediaspace");</script></div>');

jQuery('#link_down').val(videolink);
jQuery('#apDiv6').hide();

return false;
});
  

  jQuery('#exitbutton').click(function(){
  var target_offset = jQuery("#corspo").offset();
  var target_top = target_offset.top-15;
  jQuery('html, body').animate({scrollTop:target_top}, 500);
  jQuery('#apDiv1').html('');
  jQuery('#apDiv1').css("background", "url('images/aa.gif') repeat;");
  jQuery('#searchbox').val('Type your search in this field ............... and press......');
  jQuery('#link_down').val('Enter URL of Video that you want download ...');
  //jQuery('#apDiv6').html('Please search something');
  jQuery('.videodiv').html('');
  jQuery('#apDiv6').hide();
  jQuery('#apDiv4').hide();
  jQuery('#apDiv2').hide();
  });
  
  jQuery('#leftbutton').click(function(){
  var target_offset = jQuery("#corspo").offset();
  var target_top = target_offset.top-15;
  jQuery('html, body').animate({scrollTop:target_top}, 500);
  jQuery('#apDiv6').hide();
  jQuery('#apDiv4').show();
  jQuery('#apDiv2').show();
  var ifimage = jQuery('.videodiv').html();
  if(ifimage == ''){
  jQuery('#errorbox').show(100).html('Please search something');
					}
					else {
					if(page==1){
					jQuery('#errorbox').show(1000).html('This is the first page.');
					}
					
					else {
					page = page-12;
					
					jasoncalling(query,12,page)
					}
						}

  });

  jQuery('#rightbutton').click(function(){
	var target_offset = jQuery("#corspo").offset();
	var target_top = target_offset.top-15;
  jQuery('html, body').animate({scrollTop:target_top}, 500);
  jQuery('#apDiv6').hide();
  jQuery('#apDiv4').show();
  jQuery('#apDiv2').show();
  var ifimage = jQuery('.videodiv').html();
  if(ifimage == ''){
  jQuery('#errorbox').show(100).html('Please search something'); 
					}
					else {
					
					page = page+12;
					
					jasoncalling(query,12,page)
						}

  });
  
  
jQuery("#searchbox").gcomplete({
		effect: true
	});

  	

 
  
  });