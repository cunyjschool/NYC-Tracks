//init functions
$(function() 
	{
	    $('#custom-nav li').prepend('<div class="dropzone"></div>');	
		
		$('#custom-nav li').draggable({
			    handle: ' > dl',
			    opacity: .8,
			    addClasses: false,
			    helper: 'clone',
			    zIndex: 100
		});

		$('#custom-nav dl, #custom-nav .dropzone').droppable(
		{
	    	accept: '#custom-nav li',
		    tolerance: 'pointer',
	    	drop: function(e, ui) 
	    	{
	        	var li = $(this).parent();
	        	var child = !$(this).hasClass('dropzone');
	        	//Add UL to first child
	        	if (child && li.children('ul').length == 0) 
	        	{
	            	li.append('<ul id="sub-menu" />');
	        	}
	        	//Make it draggable
	        	if (child) 
	        	{
	            	li.children('ul').append(ui.draggable);
	        	}
	        	else 
	        	{
	            	li.before(ui.draggable);
	        	}

	        	li.find('dl,.dropzone').css({ backgroundColor: '', borderColor: '' });
	        	
	        	var draggablevalue = ui.draggable.attr('value');
	        	var droppablevalue = li.attr('value');
	        	li.find('#menu-' + draggablevalue).find('#parent' + draggablevalue).val(droppablevalue); 
	        	$(this).parent().find("dt").removeAttr('style');
	        	$(this).parent().find("div:first").removeAttr('style');
	    	},
	    	over: function() 
	    	{
	    		//Add child
	    		if ($(this).attr('class') == 'dropzone ui-droppable') 
	    		{
	    			$(this).parent().find("div:first").css('background', 'none').css('height', '50px');
	    		}
	    		//Add above
	    		else if ($(this).attr('class') == 'ui-droppable') 
	    		{
	    			$(this).parent().find("dt:first").css('background', '#d8d8d8');
	    		}
	    		//do nothing
	    		else {
	    		
	    		}
	    		var parentid = $(this).parent().attr('id');
		        
	       	},
	    	out: function() 
	    	{
	        	$(this).parent().find("dt").removeAttr('style');
	        	$(this).parent().find("div:first").removeAttr('style');
	        	$(this).filter('.dropzone').css({ borderColor: '' });
	    	}
	    		
		});
				 
		
	
		$('#save_top').click(function()
		{
			updatepostdata();
		});
		$('#save_bottom').click(function()
		{
			updatepostdata();
		});
		

	});


