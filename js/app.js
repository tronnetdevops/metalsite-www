// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(function(){
	$.extend(window.sb, {
		"init": function(){
			sb.events.init();
			sb.router.init();
		},
		"events": {
			"hub": {},
			"init": function(){
				$(sb.events.hub).on("error", function(e, message){
					sb.utilities.alert(message, "error");
				});
				
				$(sb.events.hub).on("message", function(e, message){
					sb.utilities.alert(message, "message");
				});
				
				$(sb.events.hub).on("success", function(e, message){
					sb.utilities.alert(message, "success");
				});
			},
			"listen": function(key, func){
				$(sb.events.hub).on(key, func);
			}
		},
		"router": {
			"init": function(){
				var params = location.href.split("?");
				
				if (params.length > 1){
					$.each(decodeURIComponent(params[1]).split("&"), function(pos,param){ 
						var vals=param.split("=");
						var key=vals.shift();
						var val=vals.join("=");
						
						$(sb.events.hub).trigger(key, [val]);
					});
				}
			},
			"goto": function(uri, postData){
				// f=document.createElement("form");
// 				f.action="me";
// 				f.method="POST";
//
// 				i=document.createElement("input");
// 				i.name="testo";
// 				i.value="presto";
//
// 				f.appendChild(i);
//
// 				f.submit();

				location.href = uri;
			}
		}
	});
	
	$("#app-content").on("click", ".sb-router-link", function(e){

	});

	
	window.sb.init();
})