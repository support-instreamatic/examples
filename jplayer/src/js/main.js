(function(){
	
	window.onload = function(){
		
		if(typeof AdMan !== "undefined"){
			
			var xman = new AdMan.Manager({
				
				admanId: 777,
				playerId: "jquery_jplayer_1",
				playerType: "jplayer",
				
				overlay: [
					{
						id:"player",
						target: "#jp_container_1",
						mode: AdMan.Overlay.Mode.REPLACE,
						animate: AdMan.Overlay.Animate.OPACITY,
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						src: "src/player.html"
					},
					{
						id:"banner",
						target: "body",
						mode: AdMan.Overlay.Mode.APPEND,
						animate: AdMan.Overlay.Animate.SLIDE,
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						showIf: [AdMan.Condition.banner()],
						src: "src/banner.html"
					}
				]
				
			});
			
		}
		
	}
	
})();