function initMap() {
	this.bind("Run", function() {
		showMenu();
	
		//recalculate weird ass bug
		var offset = Crafty.DOM.inner(Crafty.stage.elem);
		Crafty.stage.x = offset.x;
		Crafty.stage.y = offset.y;
		
		$map = $("#map");
		var map = Crafty.e("2D, DOM, Image, Draggable").image("assets/images/map.png").parent($map[0]);
		
		map.bind("Click", function(e) {
			var x = e.realX - this.x,
				y = e.realY - this.y,
				world, location, poly, wobj;
				
			//check for the first location the point is in
			for(world in MAP) {
				wobj = MAP[world];
				for(location in wobj) {
					poly = wobj[location];
					if(poly.containsPoint(x, y)) {
						console.log("CLICK", world, location);
					}
				}
			}
		});
	});
}

TOWNS = {
	fire: "Ember Town",
	lava: "Magma City",
	gas: "Nitroville",
	jungle: "Camp Foliage",
	ice: "Crystal City",
	rock: "Stone Mountain",
	water: "Aqua Town"
}