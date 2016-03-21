(function(){
	
	window.onload = function(){
		
		if(typeof AdMan !== "undefined"){
			
			var xman = new AdMan.Manager({
				
				admanId: 777,
				playerId: "myPlayer",
				playerType: "uppod_swf",
				
				overlay: [
					{
						id: "player",
						target: "#myPlayer",
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						src: "src/player.html",
						mode: AdMan.Overlay.Mode.AFTER,
						animate: AdMan.Overlay.Animate.SLIDE
					},
					{
						id: "banner",
						target: "#myPlayer",
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						showIf: [AdMan.Condition.banner()],
						src: "src/banner.html",
						mode: AdMan.Overlay.Mode.AFTER,
						animate: AdMan.Overlay.Animate.OPACITY
					}
				]
				
			});
			
		}
		
	}
	
})();