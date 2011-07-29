function initLogin() {
	Crafty.background("url('assets/images/battle/forest.png') no-repeat");
	
	$("#login a.button").click(function() {
		var data = {};
		data.username = $("#login-user").val();
		data.password = $("#login-pass").val();
	
		api("Login", data, function(data) {
			ME = data;
			inBattle();
		});
	});
	
	$("#reg-button").click(function() {
		Register.run();
	});
}

function initRegister() {
	$("#register a.button").click(function() {
		var data = {};
		data.username = $("#reg-user").val();
		data.password = $("#reg-pass").val();
		data.email = $("#reg-email").val();
		
		api("Register", data, function(data) {
			ME = data;
			Choose.run();
		});
	});
	
	$("#log-button").click(function() {
		Login.run();
	});
}

function initChoose() {
	$("#choose .box").click(function() {
		//trim off the box class
		var choice = $(this).index() + 1;
		
		api("Choose", {choice: choice}, function(data) {
			ALIENS.push(data);
			Map.run();
		});
	});
}

function inBattle() {
	//if in a battle, send them straight there
	if(ME.battleID) {
		api("HasStarted", function(data) {
			Battle.run(data);
		});
	}
}