function request(action, data, callback) {
	$.ajax("../api.php?action=" + action, {
		dataType: "html",
		data: data,
		success: callback
	});
}

//DOCUMENT READY
$(document).ready(function(){

module("Players");

/**
* Login Tests
*/
asyncTest("Login", function() {

	//incorrect details
	request("Login", {username: "Louis", password: "blah"}, function(data) {
		equals(data, "{error: 'Details incorrect'}", "Incorrect Login");
		start();
	});
	stop();
	
	//correct details
	request("Login", {username: "Louis", password: "test"}, function(data) {
		equals(data, "{\"playerID\":\"1\",\"screenName\":\"Louis\",\"email\":\"test\",\"wins\":\"0\",\"loses\":\"0\",\"money\":\"20\",\"status\":\"online\",\"location\":\"none\",\"battleID\":null}", "Correct Login");
		start();
	});
});

/**
* Registration Tests
*/
asyncTest("Register", function() {
	//username taken
	request("Register", {username: "Louis", password: "test", email: "test@test.com"}, function(data) {
		equals(data, "{error: 'Username taken'}", "Username Taken");
		start();
	});
	
	stop();
	
	//valid registration
	var newuser = "User"+(+new Date());
	request("Register", {username: newuser, password: "test", email: newuser+"@test.com"}, function(data) {
		ok((data.indexOf("{\"playerID\":") != -1), "Valid Registration");
		start();
	});
});

module("Items");

asyncTest("BuyItem", function() {
	request("BuyItem", {item: 1}, function(data) {
		console.log(data);
		equals(data, "{status: 'ok'}", "Successful Item");
		start();
	});
});

//END READY
});