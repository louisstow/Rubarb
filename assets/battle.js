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

function pop(type, msg) {
	var $pop = $("#"+type+"-pop");
	$pop.show().html(msg).animate({top: 130, opacity: 1.0}, function() {
		$(this).delay(100).animate({opacity: 0.2, top: 0}, function() {
			$(this).hide().html("").css({top: 170});
		}); 
	});
}

function effect(type, origin) {
	var amount = Crafty.randRange(3, 8),
		i = 0;
	
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