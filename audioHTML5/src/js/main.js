(function(){
	
	window.onload = function(){
		
		if(typeof AdMan !== "undefined"){
			
			var xman = new AdMan.Manager({
				
				admanId: 777,
				playerId: "audio_player",
				playerType: "tag_audio",
				
				overlay: [
					{
						id: "player",
						target: "#audio_player",
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						mode: AdMan.Overlay.Mode.REPLACE,
						animate: AdMan.Overlay.Animate.OPACITY,
						src: "src/player.html"
					},
					{
						id: "banner",
						target: "body",
						showOn: ["adStarted"],
						closeOn: ["adCompleted"],
						mode: AdMan.Overlay.Mode.APPEND,
						animate: AdMan.Overlay.Animate.SLIDE,
						src: "src/banner.html"
					}
				]
				
			});
			
		}
		
	}
	
})();