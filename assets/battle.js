function initBattle(){
	var $battlelist = $("#battle-list"),
		leftEnt, rightEnt;
	
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
			
		//bind forfeit buttons
		$("#battle-menu a.forfeit").click(function() {
			var a = $(this).text();
			
			if(a === "Forfeit") {
				api("Forfeit", param, function() {
					Lobby.run();
				});
			} else {
				api("Exit", param, function() {
					Lobby.run();
				});
			}
		});
			
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
		else $("#battle-exit").remove();
		
		//start the timer
		if(data.battle.type === "pvp" && data.battle.status !== "waiting") {
			timer(data.battle.turn == ME.playerID);
		}
		
		function clicker() {
			//if we haven't started, keep polling
			if(!STARTED) {
				api("HasStarted", param, function(resp) {
					if(resp.battle && resp.battle.status === "accepted") {
						STARTED = true;
						rightEnt.active();
						if(resp.battle.type === "pvp") timer(resp.battle.turn == ME.playerID);
						$("#battle-exit").remove();
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
					if(LAST_STATUS !== status.turn) {
						if(status.turn == ME.playerID) {
							LOCK = true;
							log("You were inactive, skipped turn", "battle");
							timer(false);
						} else {
							log("Opponent inactive, your turn", "battle");
							LOCK = false;
							timer(true);
						}
					}
					
					LAST_STATUS = status.turn;
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
						if(data.battle.type === "pvp") timer(true);
						
						if(status.replace) {
							log(me.alienAlias + " fainted. Go " + status.replace.alienAlias, "battle");
							me = status.replace;
							update("battle", "left", me);
							
							leftEnt.destroy();
							leftEnt = Crafty.e("Alien").Alien(IDtoAlien[status.replace.species].name);
							leftEnt.flip();
							leftEnt.position(200, 200);
						}
					});
				}
			});
		}
		
		function handleMove() {
			this.find(".move").click(function() {
				if(!STARTED || LOCK) return;
				
				var indicator = $(this).find("b:first"),
					id = $(this).attr("data-id"),
					param = (data.battle.type == "pvp") ? {move: id} : {move: id, battle: data.battle.battleID};
				
				api("Attack", param, function(resp) {
					LOCK = true;
					//decrement if success
					indicator.html(Crafty.n(indicator.text()) - 1);
					
					runMove("battle", resp, leftEnt, rightEnt, title, otitle, function() {
						LOCK = false;
						if(data.battle.type === "pvp") timer(false);
						
						if(resp.replace) {
							log(opp.alienAlias + " fainted. Go " + resp.replace.alienAlias, "battle");
							opp = resp.replace;
							update("battle", "right", opp);
							rightEnt.destroy();
							rightEnt = Crafty.e("Alien").Alien(IDtoAlien[resp.replace.species].name);
							rightEnt.position(600, 200);
						}
					});
				});
			});
		}
		
		//handle clicks
		$("#battle-menu a.tab").click(function() {
			var b = $(this).text(),
				$list = $("#battle-list .inner");
				
			$("#battle-menu a.tab").removeClass("active");
			$(this).addClass("active");
			
			if(b == "Attack") {
				listAttacks($list, me.alienID, handleMove);
			} else if(b === "Items") {
				listItems($list, function() {
					this.find(".item").click(function() {
						if(!STARTED || LOCK) return;
						LOCK = true;
						
						var id = $(this).attr("data-id"),
							indicator = $(this).find("b:first"),
							param = {alien: me.alienID, item: id, battle: data.battle.battleID};
						
						if(data.battle.type === "pvp") delete param.battle;
						
						api("UseItem", param, function(item) {
							LOCK = false;
							indicator.text(Crafty.n(indicator.text()) - 1);
							runMove("battle", item, leftEnt, rightEnt, title, otitle);
						});
					});
				});
			} else if(b === "Topians") {
				listTopians($list);
			}
		});
		
		listAttacks($list, me.alienID, handleMove);
		
		Crafty.bind("Clock", clicker);
		
	}).bind("Exit", function() {
		//remove the entities
		console.log("EXITING", leftEnt, rightEnt);
		leftEnt.destroy();
		rightEnt.destroy();
		
		$("#battle-log").html("");
		
		Crafty.unbind("Clock");
		$("#battle-menu a.forfeit").unbind("click");
	});
}