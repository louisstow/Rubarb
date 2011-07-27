function initLobby() {
	this.bind("Run", function() {
		var $friends = $("#lobby-friends div"),
			$all = $("#lobby-all div");
			
		api("List", function(data) {
			var i = 0, l = data.friends.length, html = "",
				player;
			
			//loop over friends
			for(;i < l; ++i) {
				player = data.friends[i];
				html += "<div class='player' data-id='"+player.playerID+"'><h3>"+player.screenName+"</h3>";
				html += "<span>Wins: <b>"+player.wins+"</b></span><span>Loses: <b>"+player.loses+"</b>";
			}
			$friends.html(html);
			
			html = "";
			//loop over all
			for(i = 0, l = data.all.length; i < l; ++i) {
				player = data.all[i];
				html += "<div class='player' data-id='"+player.playerID+"'><h3>"+player.screenName+"</h3>";
				html += "<span>Wins: <b>"+player.wins+"</b></span><span>Loses: <b>"+player.loses+"</b>";
			}
			
			$all.html(html);
		});
	});
}