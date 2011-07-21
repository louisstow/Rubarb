function initItems() {
	this.bind("Run", function() {
		$items = $("#items");
		
		api("GetItems", function(data) {
			var i = 0, l = data.length,
				html = "", item;
			
			for(;i < l; ++i) {
				item = data[i];
				html += "<div class='item box' data-id='"+item.itemID+"'><div class='profile i"+item.itemID+"'></div><h2>"+item.itemName+"</h2>";
				html += "<span>Quantity: <b>"+item.quantity+"</b></span>"+item.itemDescr;
				html += "<div class='actions'><a class='use'>Use</a></div></div>";
			}
			
			$items.html(html);
			
			$items.find("a.use").click(function() {
				var itemID = $(this).parent().parent().attr("data-id");
				$items.hide();
				
				$("<div/>", {id: "aliens"}).appendTo(Crafty.stage.elem);
				listAliens("<a class='use'>Use Item on me</a>", function() {
					$("#aliens a.use").click(function() {
						var alienID = $(this).parent().parent().attr("data-id");
						
						api("UseItem", {item: itemID, alien: alienID}, function() {
							$("#aliens").remove();
							Items.run();
							$items.html("").show();
						});
					});
				});
			});
		});
	});
}