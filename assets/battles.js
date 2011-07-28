function initBattles() {
	this.bind("Run", function() {
		var $battle = $("#battles");
		
		api("InBattle", function(data) {
			var i = 0, l = data.length, html = "", battle, turn;
			
			for(;i < l; ++i) {
				battle = data[i];
				turn = battle.turn === ME.playerID ? "Me" : battle.screenName;
				
				html += "<div class='box list2' data-id='"+battle.battleID+"'><h2>"+battle.screenName+"</h2>"
				html += "<div class='actions'><a>Play</a> <a>Forfeit</a></div>"
				html += "<span>Turn: <b>"+turn+"</b></span> <span>Last Move: <b>"+battle.lastActive+"</b></span> ";
				html += "<span>Location: <b>"+TOWNS[battle.environment]+"</b></span></div>";
			}
			
			$battle.html(html);
			
			$battle.find("a").click(function() {
				var id = $(this).parent().parent().attr("data-id"),
					action = $(this).text();
				
				if(action === "Play") {
					api("HasStarted", {battle: id}, function(resp) {
						Battle.run(resp);
					});
				} else if(action === "Forfeit") {
					api("Forfeit", {battle: id});
				}
			});
		});
	});
}