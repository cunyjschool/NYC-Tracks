function removeitem(o) 
{
	   		
	var todelete = document.getElementById('menu-' + o);
	
	if (todelete)
	{		
		var parenttodelete = document.getElementById('menu-' + o).parentNode;
        throwaway_node = parenttodelete.removeChild(todelete); 
	}	
			
	updatepostdata();
};

function updatepostdata() 
{	       		
	var i = 0;
	 $("#custom-nav").find("li").each(function(i) {

     	var j = $(this).attr('value');

     	$(this).find('#position' + j).attr('value', i);
     	$(this).attr('id','menu-' + i);
     	$(this).attr('value', i);
     	
     	$(this).find('#dbid' + j).attr('name','dbid' + i);
     	$(this).find('#dbid' + j).attr('id','dbid' + i);
     	
		$(this).find('#postmenu' + j).attr('name','postmenu' + i);
     	$(this).find('#postmenu' + j).attr('id','postmenu' + i);
     	
     	$(this).find('#parent' + j).attr('name','parent' + i);
     	$(this).find('#parent' + j).attr('id','parent' + i);
     	     	
     	$(this).find('#title' + j).attr('name','title' + i);
     	$(this).find('#title' + j).attr('id','title' + i);
     	
     	$(this).find('#linkurl' + j).attr('name','linkurl' + i);
     	$(this).find('#linkurl' + j).attr('id','linkurl' + i);
     		
     	$(this).find('#description' + j).attr('name','description' + i);
     	$(this).find('#description' + j).attr('id','description' + i);
     	
     	$(this).find('#icon' + j).attr('name','icon' + i);
     	$(this).find('#icon' + j).attr('id','icon' + i);
     	
     	$(this).find('#position' + j).attr('name','position' + i);
     	$(this).find('#position' + j).attr('id','position' + i);
     	
     	$(this).find('#linktype' + j).attr('name','linktype' + i);
     	$(this).find('#linktype' + j).attr('id','linktype' + i);
     	
     	$(this).find('dl > dt > span > #remove' + j).attr('value', i);
     	$(this).find('dl > dt > span > #remove' + j).attr('onClick', 'removeitem(' + i + ')');
     	$(this).find('dl > dt > span > #remove' + j).attr('id','remove' + i);

     	i = i + 1;
     	$('#licount').attr('value',i);

   });
   
   
	
};	
   
function appendToList(templatedir,additemtype,itemtext,itemurl,itemid,itemparentid) 
{
	var inputvaluevarname = '';
	var inputvaluevarurl = '';
	var inputitemid = '';
	var inputparentid= '';
	var inputdescription = '';
	var inputicon = '';

	if (additemtype == 'Custom') 
	{
		inputvaluevarname = document.getElementById('custom_menu_item_name').value;
		inputvaluevarurl = document.getElementById('custom_menu_item_url').value;
		inputitemid = '';
		inputparentid = '';
		inputlinktype = 'custom';
		inputdescription = document.getElementById('custom_menu_item_description').value;
	}
	else if (additemtype == 'Page')
	{
		inputvaluevarname = itemtext.toString();
		inputvaluevarurl = itemurl.toString();
		inputitemid = itemid.toString();
		inputparentid = '0';
		inputlinktype = 'page';
		
	}
	else if (additemtype == 'Category')
	{
		inputvaluevarname = itemtext.toString();
		inputvaluevarurl = itemurl.toString();
		inputitemid = itemid.toString();
		inputparentid = '0';
		inputlinktype = 'category';
	}
	else 
	{
		inputvaluevarname = '';
		inputvaluevarname = '';
		inputitemid = '';
		inputparentid = '';
		inputlinktype = 'custom';
		inputdescription = '';
	}

	
	
	var count=document.getElementById('custom-nav').getElementsByTagName('li').length;

	var randomnumber = count;

	var validatetest = 0;

	try 
	{
		var test=document.getElementById("menu-" + randomnumber.toString()).value;
	}
	catch (err) 
	{
		validatetest = 1;
	}

	while (validatetest == 0) 
	{
		randomnumber = randomnumber + 1;

		try 
		{
			var test2=document.getElementById("menu-" + randomnumber.toString()).value;
		}
		catch (err) 
		{
			validatetest = 1;
		}
	}
	
	$('#custom-nav').append('<li id="menu-' + randomnumber + '" value="' + randomnumber + '"><div class="dropzone ui-droppable"></div><dl class="ui-droppable"><dt><span class="title">' + inputvaluevarname + '</span><span class="controls"><a id="remove' + randomnumber + '" onclick="removeitem(' + randomnumber + ')" value="' + randomnumber +'"><img class="remove" alt="Remove from Custom Menu" title="Remove from Custom Menu" src="' + templatedir + '/functions/images/ico-close.png" /></a> <a href="' + inputvaluevarurl + '" target="_blank"><img alt="View Custom Link" title="View Custom Link" src="' + templatedir + '/functions/images/ico-viewpage.png" /></a></span></dt></dl><a class="hide" href="' + inputvaluevarurl + '">' + inputvaluevarname + '</a><input type="hidden" name="postmenu' + randomnumber + '" id="postmenu' + randomnumber + '" value="' + inputitemid + '" /><input type="hidden" name="parent' + randomnumber + '" id="parent' + randomnumber + '" value="' + inputparentid + '" /><input type="hidden" name="title' + randomnumber + '" id="title' + randomnumber + '" value="' + inputvaluevarname + '" /><input type="hidden" name="linkurl' + randomnumber + '" id="linkurl' + randomnumber + '" value="' + inputvaluevarurl + '" /><input type="hidden" name="description' + randomnumber + '" id="description' + randomnumber + '" value="' + inputdescription + '" /><input type="hidden" name="icon' + randomnumber + '" id="icon' + randomnumber + '" value="' + inputicon + '" /><input type="hidden" name="position' + randomnumber + '" id="position' + randomnumber + '" value="' + randomnumber + '" /><input type="hidden" name="linktype' + randomnumber + '" id="linktype' + randomnumber + '" value="' + inputlinktype + '" /></li>');

	$('#menu-' + randomnumber + '').draggable(
	{
		handle: ' > dl',
		opacity: .8,
		addClasses: false,
		helper: 'clone',
		zIndex: 100
	});

	$('#menu-' + randomnumber + ' dl, #menu-' + randomnumber + ' .dropzone').droppable({
		accept: '#' + randomnumber + ', #custom-nav li',
		tolerance: 'pointer',
		drop: function(e, ui) 
		{
			var li = $(this).parent();
			var child = !$(this).hasClass('dropzone');
			//Append UL to first child
			if (child && li.children('ul').length == 0) 
			{
				li.append('<ul/>');
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

	updatepostdata();
};



