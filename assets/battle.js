function initBattle(){
	var $battlelist = $("#battle-list"),
		leftEnt, rightEnt;
	
	$("#battle-menu a.forfeit").click(function() {
		api("Forfeit", function() {
			Lobby.run();
		});
	});

	this.bind("Run", function(data) {
		
		var p1Info = IDtoAlien[data.p1.species],
			p2Info = IDtoAlien[data.p2.species],
			
			$list = $("#battle-list .inner"),
			$pop = $("#battle-pop"),
			
			param = data.battle.type === "pvp" ? {} : {battle: data.battle.battleID},
			
			LAST_STATUS,
			
			title, otitle,
			
			me, opp,
			STARTED = (data.battle.status === "accepted");
			
		//show me on the right
		if(data.p1.playerID === ME.playerID) {
			leftEnt = Crafty.e("Alien").Alien(p1Info.name);
			rightEnt = Crafty.e("Alien").Alien(p2Info.name);
			
			title = "p1";
			otitle = "p2";
		} else {
			rightEnt = Crafty.e("Alien").Alien(p1Info.name);
			leftEnt = Crafty.e("Alien").Alien(p2Info.name);
			
			title = "p2";
			otitle = "p1";
		}
		
		me = data[title];
		opp = data[otitle];
		
		update("battle", "left", data[title]);
		update("battle", "right", data[otitle]);
			
		//position the aliens
		leftEnt.flip();
		leftEnt.position(200, 200);
		rightEnt.position(600, 200);
		
		if(data.battle.status === "waiting") rightEnt.inactive();
		
		function clicker() {
			//if we haven't started, keep polling
			if(!STARTED) {
				api("HasStarted", param, function(resp) {
					if(resp.battle && resp.battle.status === "accepted") {
						STARTED = true;
						rightEnt.active();
					}
				});
				return;
			}
			
			api("Status", param, function(status) {
				if(!status) {
					console.log(status);
					return;
				}
				
				LOCK = false;
				//if inactive;
				if(status.action === "inactive") {
					if(LAST_STATUS !== "inactive") {
						if(status.turn == ME.playerID) {
							LOCK = true;
							log("You were inactive, skipped turn", "battle");
						} else {
							log("Opponent inactive, your turn", "battle");
							LOCK = false;
						}
					}
					
					LAST_STATUS = "inactive";
					return;
				}
				
				//only do something with a new status
				if(LAST_STATUS != status.turn) {
					LAST_STATUS = status.turn;
					
					//only animate the other players turn, not mine
					if(status.turn == ME.playerID) {
						console.log("MY DATA", status);
						return;
					}
					
					LOCK = true;
					runMove("battle", status, rightEnt, leftEnt, otitle, title, function() {
						LOCK = false;
					});
				}
			});
		}
		
		listAttacks($list, me.alienID, function() {
			this.find(".move").click(function() {
				if(!STARTED || LOCK) return;
				LOCK = true;
				
				var indicator = $(this).find("b:first"),
					id = $(this).attr("data-id"),
					param = (data.battle.type == "pvp") ? {move: id} : {move: id, battle: data.battle.battleID};
				
				api("Attack", param, function(resp) {
					//decrement if success
					indicator.html(Crafty.n(indicator.text()) - 1);
					
					runMove("battle", resp, leftEnt, rightEnt, title, otitle, function() {
						LOCK = false;
					});
				});
			});
		});
		
		Crafty.bind("Clock", clicker);
		
	}).bind("Exit", function() {
		//remove the entities
		leftEnt.destroy();
		rightEnt.destroy();
		
		Crafty.unbind("Clock");
	});
}