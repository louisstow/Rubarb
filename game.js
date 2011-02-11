$(function() {
	Crafty.init(600,500);
	
	Crafty.load(["assets/images/map.png"], function() {
		Crafty.scene("main");
	});
	
	Crafty.scene("main", function() {
		Crafty.e("2D, DOM, image").attr({w: 600, h:500}).image("assets/images/map.png", "no-repeat");
		
		//FOREST
		Crafty.e("2D, mouse").attr({w:1,h:1}).areaMap(new Crafty.polygon([151,36],[226,36],[211,95],[117,95])).bind("mouseover", function() {
			console.log("Forest");
		});
		
		//CAVES
		Crafty.e("2D, mouse").attr({w:1,h:1}).areaMap(new Crafty.polygon([284,14],[493,75],[343,97])).bind("mouseover", function() {
			console.log("Caves");
		});
		
		//TOWN
		Crafty.e("2D, mouse").attr({w:1,h:1}).areaMap(new Crafty.polygon([142,239],[194,241],[228,290],[213,317],[132,275])).bind("mouseover", function() {
			console.log("Town");
		});
		
		//CITY
		Crafty.e("2D, mouse").attr({w:1,h:1}).areaMap(new Crafty.polygon([353,266],[393,266],[434,324],[353,325])).bind("mouseover", function() {
			console.log("City");
		});
		
		//DOCK
		Crafty.e("2D, mouse").attr({w:1,h:1}).areaMap(new Crafty.polygon([324,414],[407,394],[419,442],[324,442])).bind("mouseover", function() {
			console.log("Dock");
		});
		
		//VOLCANO
		Crafty.e("2D, mouse").attr({w:1,h:1}).areaMap(new Crafty.polygon([273,95],[338,95],[386,194],[305,233],[204,194])).bind("mouseover", function() {
			console.log("Volcano");
		});
	});
});