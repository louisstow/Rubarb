function initBattle(){
	var $battlelist = $("#battle-list"),
		p1,	p2;
	
	$("#battle-menu a.forfeit").click(function() {
		api("Forfeit", function() {
			Lobby.run();
		});
	});

	this.bind("Run", function(data) {
		
		var alienInfo = IDtoAlien[data.p1.species],
			oppInfo = IDtoAlien[data.p2.species],
			$list = $("#battle-list .inner"),
			$pop = $("#battle-pop"),
			param = data.battle.type === "pvp" ? {} : {battle: data.battle.battleID},
			LAST_STATUS,
			STARTED = (data.battle.status === "accepted"),
			TURN = data.battle.turn,
			me,
			opp;
			
		//show me on the right
		if(data.p1.playerID === ME.playerID) {
			p1 = Crafty.e("Alien").Alien(alienInfo.name);
			p2 = Crafty.e("Alien").Alien(oppInfo.name);
			
			me = data.p1;
			opp = data.p2;
			
			update("battle", "left", data.p1);
			update("battle", "right", data.p2);
		} else {
			p2 = Crafty.e("Alien").Alien(alienInfo.name);
			p1 = Crafty.e("Alien").Alien(oppInfo.name);
			
			me = data.p2;
			opp = data.p1;
			
			update("battle", "left", data.p2);
			update("battle", "right", data.p1);
		}
			
		//position the aliens
		p1.flip();
		p1.position(200, 200);
		p2.position(600, 200);
		
		if(data.battle.status === "waiting") p2.inactive();
		
		function clicker() {
			//if we haven't started, keep polling
			if(!STARTED) {
				api("HasStarted", param, function(resp) {
					if(resp.battle.status === "accepted") {
						STARTED = true;
						p2.active();
					}
				});
				return;
			}
			
			api("Status", param, function(status) {
				//only do something with a new status
				console.log(LAST_STATUS, status.turn, LAST_STATUS == status.turn);
				if(LAST_STATUS != status.turn && status.turn != ME.playerID) {
					LAST_STATUS = status.turn;
					console.log(status);
					var turn, pA, oA;
					
					if(status.p1.playerID == ME.playerID) {
						turn = status.p1;
						pA = p1;
						oA = p2;
					} else {
						turn = status.p2;
						pA = p2;
						oA = p1;
					}
					
					LOCK = true;
					runMove("battle", status, pA, oA, turn, function() {
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
					
				indicator.html(Crafty.n(indicator.text()) - 1);
				
				api("Attack", param, function(resp) {
					var turn = resp.p1.playerID === ME.playerID ? resp.p1 : resp.p2;
					
					runMove("battle", resp, p1, p2, turn, function() {
						LOCK = false;
					});
				});
			});
		});
		
		Crafty.bind("Clock", clicker);
		
	}).bind("Exit", function() {
		//remove the entities
		if(p1) p1.destroy();
		if(p2) p2.destroy();
		
		Crafty.unbind("Clock");
	});
}