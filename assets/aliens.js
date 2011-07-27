function initAliens() {
	
	this.bind("Run", function() {
		
		var action = "<a class='rename'>Rename</a><a class='up'>Up</a><a class='down'>Down</a>",
			$aliens = $("#aliens");
		
		//list the aliens
		listAliens(action, function() {
			$aliens.find("a.rename").click(function() {
				var	$h2 = $(this).parent().parent().children("h2"),
					old = $h2.text(),
					id = $(this).parent().parent().attr("data-id");
					
				$h2.html("<input type='text' class='text' id='rename' value='"+old+"'/>");
				$("#rename").focus().blur(function() {
					var newname = $(this).val();
						
					if(newname !== old) {
						api("RenameAlien", {alien: id, name: newname}, function() {
							$h2.html(newname);
							$(this).remove();
						});
					} else {
						$h2.html(old);
						$(this).remove();
					}
				});
			});
			
			$aliens.find("a.up").click(function() {
				var move = $(this).parent().parent();
				move.insertBefore(move.prev());
				saveOrder();
			});
			
			$aliens.find("a.down").click(function() {
				var move = $(this).parent().parent();
				move.insertAfter(move.next());
				saveOrder();
			});
			
			//update the order
			function saveOrder() {
				var data = {},
					i = 1;
				$("#aliens div.alien").each(function() {
					data[i++] = $(this).attr("data-id");
				});
				
				api("Reorder", data);
			}
		});
	});
}

function getHealthBar(hp, max) {
	var color, html,
		level = ~~(hp / max * 100);
	
	if(level > 50) {
		color = "#54e432";
	} else if(level > 20) {
		color = "#e4bc32";
	} else {
		color = "#e43232";
	}
	
	if(level > 100) {
		level = 100;
	}
	
	html = "<div class='health'><div style='width:"+level+"%; background: "+color+"'></div></div>";
	return html;
}

/**
* Generate a list of Aliens
*/
function listAliens(actions, callback) {
	api("GetAliens", function(data) {
		var i = 0, l = data.length,
			html = "", alien,
			$aliens = $("#aliens");
		
		for(;i < l; ++i) {
			alien = data[i];
			html += "<div class='alien box' data-id='"+alien.alienID+"'><div class='profile s"+alien.species+"'></div><h2>"+alien.alienAlias+"</h2>";
			html += getHealthBar(alien.hp, alien.maxHP);
			html += "<span>Speed: <b>" + alien.speed + "</b></span><span>Attack: <b>" + alien.attack + "</b></span><span>Defense: <b>" + alien.defense;
			html += "</b></span><span>EXP: <b>" + alien.exp + "</b></span>";
			html += "<div class='actions'>"+actions+"</div></div>";
		}
		
		$aliens.html(html);
		callback();
		
		//update the aliens array
		ALIENS = data;
	});
}

/**
* Alien Component
*/
Crafty.c("Alien", {
	head: null,
	body: null,
	tail: null,
	front: null,
	back: null,
	
	parent: null,
	amount: 0,
	x: 0,
	y: 0,
	
	init: function() {
		this.parent = document.createElement("div");
		this.parent.style.position = "absolute";
		Crafty.stage.inner.appendChild(this.parent);
	},
	
	Alien: function(type) {
		var comps = "2D, DOM, Animation, " + type,
			parts = "head body tail front back".split(' '),
			part, prop;
		
		for(part in parts) {
			part = parts[part];
			
			//only set if defined
			if(Crafty.isComp(type + "_" + part)) {
				this[part] = Crafty.e(comps + "_" + part);
				
				//move to the container
				Crafty.stage.inner.removeChild(this[part]._element);
				this.parent.appendChild(this[part]._element);
				
				this.amount++;
			}
		}
		
		
		this.assemble(ANIMATIONS[type].main);
		
		for(var reel in ANIMATIONS[type]) {
			this.apply(reel, ANIMATIONS[type][reel]);
		}
		
		return this;
	},
	
	assemble: function(props) {
		var parts = "head body tail front back".split(' '),
			part, prop;
		
		for(part in parts) {
			part = parts[part];
			
			//if positions defined, apply
			if(props[part]) {
				
				//loop over props and apply
				for(prop in props[part]) {
					this[part][prop] = props[part][prop];
				}
			}
		}
	},
	
	apply: function(label, anim) {
		var parts = "head body tail front back".split(' '),
			part,
			reel,
			key;
			
		//for every body part
		for(part in parts) {
			part = parts[part];
			if(!this[part]) continue;
			
			reel = {};
			//add the animation
			for(key in anim) {
				reel[key] = anim[key][part];
			}
			
			this[part].addAnimation(label, reel);
		}
		
		return this;
	},
	
	position: function(x, y) {
		var style = this.parent.style;
		this.x = x;
		this.y = y;
		
		style.left = x + "px";
		style.top = y + "px";
	},
	
	run: function(label) {
		var parts = "head body tail front back".split(' '),
			part, count = 0, self = this;
			
		//for every body part
		for(part in parts) {
			part = parts[part];
			if(!this[part]) continue;
			
			this[part].bind("AnimationEnd", function check() {
				if(++count >= self.amount) {
					self.trigger("AnimationEnd");
				}
				
				//unbind it
				this.unbind("AnimationEnd", check);
			});
			
			this[part].playAnimation(label);
		}
		
		return this;
	},
	
	flip: function() {
		this.parent.setAttribute("class", "flip");
		var w = $(this.parent).width();
		this.position(this.x + w, this.y);
	}
});

/**
* Map the species ID to
* the name
*/
var IDtoAlien = {
	1: {name: "Possel", world: "fire"},
	2: {name: "Stilcer", world: "fire"},
	3: {name: "Pyrock", world: "fire"},
	4: {name: "Skarrier", world: "ice"},
	5: {name: "Vyel", world: "ice"},
	6: {name: "Kriskross", world: "ice"},
	7: {name: "Dooth", world: "water"},
	8: {name: "Alliman", world: "water"},
	9: {name: "Triclee", world: "water"},
	10: {name: "Apelim", world: "jungle"},
	11: {name: "Wormbo", world: "jungle"},
	12: {name: "Scorn", world: "jungle"},
	13: {name: "Ent", world: "jungle"},
	14: {name: "Drillst", world: "rock"},
	15: {name: "Hechop", world: "rock"},
	16: {name: "Diggimal", world: "rock"},
	17: {name: "Samalanda", world: "lava"},
	18: {name: "Serenifly", world: "lava"},
	19: {name: "Fubar", world: "lava"},
	20: {name: "Sepelem", world: "gas"},
	21: {name: "Enmesh", world: "gas"},
	22: {name: "Modflap", world: "gas"}
}