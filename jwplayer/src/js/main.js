(function(){
	
	window.onload = function(){
		
		if(typeof AdMan !== "undefined"){
			
			var xman = new AdMan.Manager({
				
				admanId: 777,
				playerId: "player",
				playerType: "jwplayer_5",
				
				overlay: [
					{
						id: "player",
						target: "body",
						mode: AdMan.Overlay.Mode.APPEND,
						animate: AdMan.Overlay.Animate.SLIDE,
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						src: "src/player.html"
					},
					{
						id: "banner",
						target: "#player_container",
						mode: AdMan.Overlay.Mode.APPEND,
						animate: AdMan.Overlay.Animate.OPACITY,
						showOn: ["adStarted"],
						showIf: [AdMan.Condition.banner()],
						src: "src/banner.html"
					}
				]
				
			});
			
		}
		
	}
	
})();