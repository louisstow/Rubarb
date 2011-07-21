/**
* Training Interface
*/
function initTraining(){
	var $trainlist = $("#train-list");
	$("#train-menu a.forfeit").click(function() {
		api("Forfeit", function() {
			Map.run();
		});
	});

	this.bind("Run", function(data) {
		update("left", data.alien);
		update("right", data.opp);
		
		var alienInfo = IDtoAlien[data.alien.species],
			oppInfo = IDtoAlien[data.opp.species],
			$list = $("#train-list .inner"),
			$pop = $("#train-pop"),
			alien = Crafty.e("Alien").Alien(alienInfo.name),
			opp = Crafty.e("Alien").Alien(oppInfo.name),
			
			LOCK = false;
			
		//position the aliens
		alien.flip();
		alien.position(200, 200);
		opp.position(600, 200);
		
		api("ListAttacks", {alien: data.alien.alienID}, function(moves) {
			var i = 0, l = moves.length, move, html = "";
			
			for(;i < l; ++i) {
				move = moves[i];
				html += "<div class='move' data-id='"+move.moveID+"'><h3>"+move.moveName+"</h3>Exp: <b>"+move.expSelf+"</b></div>";
			}
			
			$list.html(html);
			$list.find(".move").click(function() {
				if(LOCK) {
					return;
				}
				
				var id = $(this).attr("data-id");
				LOCK = true;
				
				api("Spar", {move: id}, function(result) {
					console.log(result);
					var anim;
					
					//start with the first turn
					if(result[0].action === "attack") {
						//play the animation
						anim = getAnimation(result[0].moveID);
						pop("train-left", result[0].move+"! -"+result[0].damage);
						
						alien.bind("AnimationEnd", function upd() {
							//update the stats
							update("left", result[0].me);
							update("right", result[0].opp);
							
							if(result[0].damage != 0) {
								effect(alienInfo.world, opp);
								effect("cloud", opp);
								opp.run("recoil");
							}
							
							//wait 2 seconds before retaliation
							setTimeout(function() {
								//then the opponents turn
								if(result[1].action === "attack") {
									//play an animation
									anim = getAnimation(result[1].moveID);
									pop("train-right", result[1].move+"! -"+result[1].damage);
									
									//wait for the animation to end before updating
									opp.bind("AnimationEnd", function upd2() {
										//update the stats
										update("left", result[1].me);
										update("right", result[1].opp);
										
										if(result[1].damage != 0) {
											effect(oppInfo.world, {x: alien.x - 100, y: alien.y});
											effect("cloud", {x: alien.x - 100, y: alien.y});
											alien.run("recoil");
										}
										LOCK = false;
										this.unbind("AnimationEnd", upd2);
									});
									
									opp.run(anim);
								}
							}, 2000);
							
							this.unbind("AnimationEnd", upd);
						});
						
						alien.run(anim);
						
					} else if(result[0].action === "missed") {
						update("left", result[0].me);
						pop("train-left", "Missed!");
						
						//then the opponents turn
						if(result[1].action === "attack") {
							//play an animation
							anim = getAnimation(result[1].moveID);
							pop("train-right", result[1].move);
							
							//wait for the animation to end before updating
							opp.bind("AnimationEnd", function upd2() {
								//update the stats
								update("left", result[1].me);
								update("right", result[1].opp);
								
								this.unbind("AnimationEnd", upd2);
								LOCK = false;
							});
							
							opp.run(anim);
						}
					}
					
					
				});
			});
		});
		
		
	});
	
	function update(side, data) {
		$side = $("#train-"+side);
		$side.find("h2").text(data.alienAlias);
		$side.find("b.speed").text(data.speed);
		$side.find("b.attack").text(data.attack);
		$side.find("b.defense").text(data.defense);
		$side.find("b.exp").text(data.exp);
		
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
}


/**
* Training Screen
*/
function initTrainScreen() {
	$("#train-screen a").click(function() {
		var choice = $(this).attr("name");
		
		api("Train", {level: choice}, function(data) {
			console.log(data);
			Training.run(data);
		});
	});
}