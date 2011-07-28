function initLobby() {
	this.bind("Run", function() {
		var $friends = $("#lobby-friends div"),
			$all = $("#lobby-all div"),
			$req = $("#lobby-requests");
			
		//list all players online
		api("List", function(data) {
			var i = 0, l = data.friends.length, html = "",
				player;
			
			//loop over friends
			for(;i < l; ++i) {
				player = data.friends[i];
				html += "<div class='player list' data-id='"+player.playerID+"'><h3>"+player.screenName+"</h3>";
				html += "<div class='actions'><a class='battle pvp'>PVP</a> <a class='battle test'>Test</a></div>";
				html += "<span>Wins: <b>"+player.wins+"</b></span> <span>Loses: <b>"+player.loses+"</b>";
			}
			$friends.html(html);
			
			html = "";
			//loop over all
			for(i = 0, l = data.all.length; i < l; ++i) {
				player = data.all[i];
				html += "<div class='player list' data-id='"+player.playerID+"'><h3>"+player.screenName+"</h3>";
				html += "<div class='actions'><a class='battle pvp'>PVP</a> <a class='battle test'>Test</a></div>";
				html += "<span>Wins: <b>"+player.wins+"</b></span> <span>Loses: <b>"+player.loses+"</b></div>";
			}
			
			$all.html(html);
			
			//request battle
			$("a.battle").click(function() {
				var type = $(this).hasClass("pvp") ? "pvp" : "test",
					friend = $(this).parent().parent().attr("data-id");
					
				console.log(type, friend);
				api("CreateBattle", {type: type, friend: friend}, function(resp) {
					console.log(resp);
					Battle.run(resp);
				});
			});
		});
		
		LOCK = false;
		Crafty.bind("Clock", function clock() {
			if(LOCK) return;

			LOCK = true;
			//list battle requests
			api("GetBattles", function(data) {
				LOCK = false;
				var i = 0, l = data.length, html = "", battle;
				
				if(!l) {
					$req.hide();
					return;
				}
				
				for(;i < l; ++i) {
					battle = data[i];
					html += "<div class='list' data-id='"+battle.battleID+"'><h3>"+battle.screenName+" ("+battle.type+")</h3>";
					html += "<div class='actions'><a>Accept</a> <a>Decline</a></div></div>";
				}
				
				$req.show();
				$req.find("div").html(html);
				$req.find("a").click(function() {
					var id = $(this).parent().parent().attr("data-id"),
						resp = $(this).text() === "Accept" ? "A" : "D";
						
					api("RespondBattle", {battle: id, response: resp}, function(battle) {
						//start the battle
						if(battle.battle) {
							Battle.run(battle);
						}
					});
					
					if(resp === "D") {
						$(this).parent().parent().remove();
						if($req.find(".list").size() === 0) {
							$req.hide();
						}
					}
				});
			});//end GetBattles
			console.log("TICK");
		}); //end Run bind
		Crafty.trigger("Clock");
	}).bind("Exit", function() {
		console.log("EXIT");
		Crafty.unbind("Clock");
	});
}