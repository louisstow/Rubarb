//(function($, Crafty) {

var Battle, Training, TrainScreen;

$(function() {
	Crafty.init(800, 600);

	Battle = Crafty.e("Room").Room("Battle", "battle-left, battle-right, battle-center, battle-menu");
	TrainScreen = Crafty.e("Room").Room("TrainScreen", "train-screen");
	Training = Crafty.e("Room").Room("Training", "train-left, train-right, train-menu");
	
	Training.bind("Run", function() {
		var $trainlist = $("#train-list");
		
		api("ListAttacks", function(data) {
			
		});
	});

	/**
	* Training Screen
	*/
	TrainScreen.bind("Run", function() {
		
		$("#train-screen a").click(function() {
			var choice = $(this).attr("name");
			console.log(choice);
			
			api("Train", {level: choice}, function(data) {
				console.log(data);
			});
		});
	});
	
	//run the training screen
	TrainScreen.run();
});

/**
* Wrap AJAX request
*/
function api(action, data, callback) {
	//allow empty data
	if(typeof data === "function") {
		callback = data;
		data = {};
	}
	
	$.ajax("api.php?action=" + action, {
		dataType: "html",
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

/**
* Container of Rooms
*/
var Rooms = (function() {
	var rooms = {},
		selected;

	return {
		/**
		* Add a Room to the collection
		*/
		add: function(id, room) {
			rooms[id] = room;
		},
		
		/**
		* Remove a room from the collection
		*/
		remove: function(id) {
			delete rooms[id];
		},
		
		/**
		* Select a Room by exiting the currently
		* selected room.
		*/
		select: function(id) {
			//exit the last room
			if(selected) {
				room[selected].exit();
			}
			
			selected = id;
		}
	};
})();

Crafty.c("Room", {
	frag: null,
	id: null,
	
	Room: function(id, ids) {
		Rooms.add(id, this);
		
		this.frag = pull(ids);
		this.id = id;
		
		return this;
	},
	
	run: function() {
		Rooms.select(this.id);
		
		Crafty.stage.elem.appendChild(this.frag);
		this.trigger("Run");
		return this;
	},
	
	exit: function() {
		Crafty.stage.elem.removeChild(this.frag);
		this.trigger("Exit");
		return this;
	}
});



//})(jQuery, Crafty);