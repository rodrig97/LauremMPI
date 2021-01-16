 

dojo.require("dojo.on");
dojo.require("dojo._base.array");
dojo.require("dojo.json");

dojo.require("dijit.dijit"); // optimize: load dijit layer
dojo.require("dijit.Declaration");
dojo.require("dijit.Toolbar");
dojo.require("dijit.ToolbarSeparator");

dojo.require("dijit.Menu");
dojo.require("dijit.MenuItem");
dojo.require("dijit.PopupMenuItem");
dojo.require("dijit.CheckedMenuItem");
dojo.require("dijit.MenuSeparator");
dojo.require("dijit.TitlePane");
dojo.require("dijit.Tooltip");
dojo.require("dijit.TooltipDialog");
dojo.require("dijit.form.DropDownButton");
dojo.require("dijit.Dialog");    
dojo.require("dijit.Calendar");
dojo.require("dijit.MenuBar");
dojo.require("dijit.MenuBarItem");
dojo.require("dijit.PopupMenuBarItem");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.DropDownButton");
dojo.require("dijit.form.ComboButton");
//dojo.require("dijit.form.ToggleButton");
dojo.require("dijit.form.Select");
dojo.require("dijit.form.MultiSelect");
dojo.require("dijit.form.FilteringSelect");

dojo.require("dijit.ProgressBar");
dojo.require("dijit.form.Form");
dojo.require("dijit.form.ComboBox");
dojo.require("dijit.form.TextBox");
dojo.require("dijit.form.ValidationTextBox");
//dojo.require("dijit.form.ValidationTextArea");
dojo.require("dijit.form.CheckBox");
//dojo.require("dojox.form.DateTextBox");
dojo.require("dijit.form.NumberSpinner");
dojo.require("dijit.form.DateTextBox");
dojo.require("dijit.form.TimeTextBox");
dojo.require("dojo.date.locale");
dojo.require("dijit.form.RadioButton");
dojo.require("dijit.form.NumberTextBox");
dojo.require("dijit.form.TextArea");
//dojo.require("dijit.ColorPalette");
dojo.require("dijit.layout.BorderContainer");
dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.AccordionContainer");
dojo.require("dijit.layout.SplitContainer");

/* 
dojo.require("dojo.fx.easing");
dojo.require("dojox.widget.Dialog");
dojo.require("dojox.layout.ExpandoPane");
dojo.require("dojox.grid.TreeGrid");
dojo.require("dojo.data.ItemFileWriteStore"); 
dojo.require("dijit.Tree");
dojo.require("dojo.data.ItemFileReadStore");
dojo.require("dojo.NodeList-traverse"); 
dojo.require("dojox.NodeList.delegate");
dojo.require("dijit.tree._dndSelector");
dojo.require("dijit.tree.dndSource");
dojo.require("dijit.Editor");
dojo.require("dijit._editor.plugins.FullScreen");
 
 dojo.require("dojo.dnd.common");
dojo.require("dojo.dnd.Source");*/
 
//on item tree , we want to drop on containers, the root node itself, or between items in the containers
	/*	function itemTreeCheckItemAcceptance(node,source,position){
			source.forInSelectedItems(function(item){
				console.log("testing to drop item of type " + item.type[0] + " and data " + item.data + ", position " + position);
			});
			var item = dijit.getEnclosingWidget(node).item;
			if(item && (item.root || myStore.hasAttribute(item,"numberOfItems"))){
				return true;
			}
			return position != "over";

		}

		
		function getIcon(item){
			if(!item || myStore.hasAttribute(item, "numberOfItems")){
				return "myFolder";
			}
			return "myItem"
		}*/
                /*
                function on_tree_drop(){
                     alert('dropp');
                }
                */
/*
dojo.require("dijit.Tree");
dojo.require("dojox.layout.FloatingPane");
dojo.require("dojox.rpc.Service");
dojo.require("dojo.io.script");
dojo.require('dojo/parser');
 */
	
/*
dojo.extend(dijit.Tree, {
    refresh: function() {
        this.dndController.selectNone();
        this.model.store.clearOnClose = true;
        this.model.store.close();

        // Completely delete every node from the dijit.Tree     
        this._itemNodesMap = {};
        this.rootNode.state = "UNCHECKED";
        this.model.root.children = null;

        // Destroy the widget
        this.rootNode.destroyRecursive();

        // Recreate the model, (with the model again)
        this.model.constructor(this.model)

        // Rebuild the tree
        this.postMixInProperties();
        this._load();
    }
});        */