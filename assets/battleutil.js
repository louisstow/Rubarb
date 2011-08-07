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
* @param turn Who is P1 and who is P2
* @param callback Function to be called when move done
*/
function runMove(type, data, player, opponent, actor, recv, callback) {
	if(data.action === "forfeit") {
		return win(data.win, "Opponent Forfeit");
	} else if(data.action === "item") {
		log(data.alien.alienAlias + " used " + data.item.itemName, type);
		update(type, (data.turn === ME.playerID ? "left" : "right"), data.alien);
		return;
	}
	
	//play the animation
	var anim = getAnimation(data.move.moveID),
		msg, 
		alien = data[actor], 
		opp = data[recv], 
		alienstats = data[actor + "stats"] || alien, 
		oppstats = data[recv + "stats"] || opp;
		
	//determine the message to log
	if(data.action === "missed") {
		msg = alien.alienAlias + " missed";
	} else if(data.damage === 0) {
		msg = alien.alienAlias + " used " + data.move.moveName;
	} else {
		msg = narrate(data.damage, data.move.moveName, alien, opp);
	}
	
	log(msg, type);
	
	player.bind("AnimationEnd", function upd() {
		//update the stats
		if(alien.playerID == ME.playerID) {
			if(alien) update(type, "left", alien, alienstats);
			if(opp) update(type, "right", opp, oppstats);
		} else {
			if(opp) update(type, "left", opp, oppstats);
			if(alien) update(type, "right", alien, alienstats);
		}
		
		//if the move resulted in damage, play animations and effects
		if(data.action !== "missed") {
			effect(data.move.moveType, opponent);
			if(data.replace) {
				opponent.bind("AnimationEnd", function() {
					opponent.unbind("AnimationEnd");
					if(callback) callback();
				});
				
				opponent.run("faint");
			} else {
				opponent.run("recoil");
			}
		}
		
		//clean up
		this.unbind("AnimationEnd", upd);
		
		//if someone won
		if(data.win) {
			if(data.turn == ME.playerID) {
				win(data.win, "You Win");
			} else {
				lose();
			}
			return;
		}
		
		//execute a callback
		if(callback && !data.replace) callback();
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

function log(text, type) {
	type = type || "train";
	var $log = $("#"+type+"-log");
	
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

var ticker = false;
function timer(turn) {
	var $timer = $("#battle-center"),
		i = 300;
	
	//stop existing ticker
	if(ticker) {
		clearInterval(ticker);
	}
	
	if(turn) {
		$timer.css("background", "blue");
	} else {
		$timer.css("background", "orange");
	}
	
	ticker = setInterval(function() {
		i--;
		var min = ~~(i / 60),
			sec = i % 60;
		
		//timer ran out
		if(i < 1) {
			Crafty.trigger("TimerUp");
			$timer.html("Time Up");
			clearInterval(ticker);
			return;
		}
		
		$timer.html(min + ":" + Crafty.zeroFill(sec, 2));
	}, 1000);
}

function win(awards, how) {
	//stop the clock
	Crafty.unbind("Clock");
	
	console.log("YOU FUCKING WIN", how, awards);
}

function lose() {
	var curtain = Crafty.e("2D, DOM, Color, Tween")
		.color("black")
		.attr({alpha: 0, w: 800, h: 600});
		
		
	var text = Crafty.e("2D, DOM, Text, Tween, statement")
		.attr({x: 100, y: 200, alpha: 0, w: 400})
		.text("You were defeated")
		.css("color", "#fff")
		.tween({alpha: 1.0}, 100);
		
	curtain.bind("TweenEnd", function() {
		this.delay(function() {
			this.destroy();
			text.destroy();
			Lobby.run();
		}, 1000);
	}).tween({alpha: 1.0}, 100);
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

function listAttacks(parent, alien, callback) {
	api("ListAttacks", {alien: alien}, function(moves) {
		var i = 0, l = moves.length, move, html = "";
		
		for(;i < l; ++i) {
			move = moves[i];
			html += "<div class='move list' data-id='"+move.moveID+"'><h3>"+move.moveName+"</h3>Left: <b>"+move.amount+"</b> / <b>"+move.maxAmount+"</b> ";
			html += "Type: <b>"+move.moveType+"</b></div>";
		}
		
		parent.html(html);
		callback.call(parent);
	});
}

function listItems(parent, callback) {
	api("GetItems", function(items) {
		var i = 0, l = items.length, item, html = "";
		
		for(;i < l; ++i) {
			item = items[i];
			html += "<div class='item list' data-id='"+item.itemID+"'><h3>"+item.itemName+"</h3>Left: <b>"+item.quantity+"</b> ";
			html += item.itemDescr+"</div>";
		}
		
		parent.html(html);
		callback.call(parent);
	});
}