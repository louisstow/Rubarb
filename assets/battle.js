function getAnimation(id) {
	var i = 0, l, move, ids;
	
	//loop over moves
	for(move in MOVES) {
		ids = MOVES[move];
		
		//loop over ids
		for(i = 0, l = ids.length; i < l; ++i) {
			//if found the id, return the move
			if(id == ids[i]) {
				return move;
			}
		}
	}
	
	//return Scratch as the default move
	return "scratch";
}

function update(type, side, data, stats) {
	//default the stats to use the data
	stats = stats || data;
	
	$side = $("#"+type+"-"+side);
	$side.find("h2").text(data.alienAlias);
	$side.find("b.speed").text(stats.speed);
	$side.find("b.attack").text(stats.attack);
	$side.find("b.defense").text(stats.defense);
	$side.find("b.lvl").text(data.level);
	$side.find("div.health-stats .left").text(data.hp);
	$side.find("div.health-stats .right").text(data.maxHP);
	
	var hp = Math.ceil(data.hp / data.maxHP * 100),
		color;
		
	if(hp > 50) {
		color = "#54e432";
	} else if(hp > 20) {
		color = "#e4bc32";
	} else {
		color = "#e43232";
	}
	
	$side.find(".health div").css({width: hp + "%", background: color});
}

/**
* Execute a move from the server
* @param type Whether the battle is training or battle
* @param data Data for the move
* @param player Topian making the move
* @param opponent Topian recieving the move
* @param turn Who is ME and who is OPP
* @param callback Function to be called when move done
*/
function runMove(type, data, player, opponent, turn, callback) {
	//play the animation
	var anim = getAnimation(data.move.moveID),
		msg, me, opp, mestats, oppstats;
		
	//decide who is ME and who is OPP
	if(turn === data.me) {
		me = data.me;
		mestats = data.mestats || me;
		opp = data.opp;
		oppstats = data.oppstats || opp;
	} else {
		me = data.opp;
		mestats = data.oppstats || me;
		opp = data.me;
		oppstats = data.mestats || opp;
	}
		
	//determine the message to log
	if(data.action === "missed") {
		msg = me.alienAlias + " missed";
	} else if(data.damage === 0) {
		msg = me.alienAlias + " used " + data.move.moveName;
	} else {
		msg = narrate(data.damage, data.move.moveName, me, opp)
	}
	
	log(msg);
	
	player.bind("AnimationEnd", function upd() {
		//update the stats
		update(type, "left", data.me, data.mestats);
		update(type, "right", data.opp, data.oppstats);
		
		//if the move resulted in damage, play animations and effects
		if(data.damage != 0) {
			effect(data.move.moveType, opponent);
			opponent.run("recoil");
		}
		
		//clean up
		this.unbind("AnimationEnd", upd);
		
		//execute a callback
		if(callback) callback();
	});
	
	player.run(anim);
}

function narrate(damage, move, player, opp) {
	var perc = ~~(damage / opp.maxHP * 100),
		narr = "",
		ext = (damage) ? " (-" + damage +"HP)" : "";
		
	if(perc > 80) {
		narr = " was shaken by ";
	} else if(perc > 50) {
		narr = " was hit by ";
	} else if(perc > 20) {
		narr = " felt ";
	} else {
		narr = " barely noticed ";
	}
	
	return opp.alienAlias + narr + move + ext;
}

function log(text) {
	var $log = $("#train-log");
	
	//remove the first span
	if($log.find("span").size() == 2) {
		$log.find("span:first").remove();
	}
	
	//add a new one
	$("<span/>").html(text).appendTo($log);
}

function effect(type, origin) {
	var amount = Crafty.randRange(3, 8),
		i = 0;
	
	if(type === "normal") type = "cloud";
	
	for(;i < amount; ++i) {
		Crafty.e("Effect").Effect(type, origin);
	}
}

Crafty.c("Effect", {
	Effect: function(type, origin) {
		this.addComponent("2D, DOM, "+type);
		this.x = origin.x;
		this.y = origin.y;
		this.z = 2;
		this.origin("center");
		
		var rspeed = Crafty.randRange(-10, 10),
			xspeed = Crafty.randRange(-10, 10),
			yspeed = Crafty.randRange(-10, 10);
		
		this.bind("EnterFrame", function() {
			this.rotation += rspeed;
			this.x += xspeed;
			this.y += yspeed;
			this.alpha -= 0.1;

			if(this.alpha <= 0) {
				this.destroy();
			}
		});
	}
});