(function($, Crafty) {

$(function() {
	Crafty.init(800, 600);
	
	Crafty.load([], function() {
		Crafty.scene("Main");
	});
	
	Crafty.scene("Loading", function() {
		
	});
	
	//play first
	//Crafty.scene("Loading");
	
	Crafty.scene("Main", function() {
		
	});
	
	Crafty.scene("Battle", Battle.run);
	Crafty.scene("Shop", runShop);
	Crafty.scene("Trading", runTrade);
	Crafty.scene("Hospital", runHospital);
	
	Battle.init();
	
	Crafty.scene("Battle");
});

/**
* Wrap AJAX request
*/
function api(action, data, callback) {
	$.ajax("api.php?action=" + action, {
		dataType: "json",
		data: data,
		success: callback
	});
};

function pull(list) {
	var frag = document.createDocumentFragment(),
		i = 0, ids = list.split(/\s*,\s*/), l = ids.length,
		elem;
		
	for(;i < l; ++i) {
		elem = document.getElementById(ids[i]);
		
		//remove from the tree
		elem.parentNode.removeChild(elem);
		frag.appendChild(elem);
	}
	
	return frag;
}

Crafty.c("Room", {
	frag: null,
	
	Room: function(ids) {
		this.frag = pull(ids);
		return this;
	},
	
	run: function() {
		Crafty.stage.elem.appendChild(this.frag);
		this.trigger("Run");
		return this;
	}
});

Battle = Crafty.e("Room").Room("battle-left, battle-right, battle-center, battle-menu");
/**
* Battle Interface
*/
Battle = {
	frag: null,
	
	init: function() {
		this.frag = pull("battle-left, battle-right, battle-center, battle-menu");
	},
	
	run: function() {
		Crafty.stage.elem.appendChild(this.frag);
	}
};



/**
* Shop Interface
*/
function runShop() {

}

/**
* Trade Interface
*/
function runTrade() {

}

/**
* Hospital Interface
*/
function runHospital() {

}

})(jQuery, Crafty);