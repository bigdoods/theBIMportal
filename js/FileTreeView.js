/**
 * File Tree View jQuery plugin 1.0
 * 
 * Copyright (c) 2014, AIWSolutions
 * License: GPL2
 * Project Website: http://wiki.aiwsolutions.net/Snld9
 **/

jQuery.fn.fileTreeView = function(expandAll, collapseAll, folderClass) {
	var root = this;
	
	function toggleState(node, expanded) {
		var isCollapsed = $(node).hasClass('treeFolderCollapsed');
		if (expanded || (expanded === undefined && isCollapsed)) {
			$(node).removeClass('treeFolderCollapsed');
		} else {
			$(node).addClass('treeFolderCollapsed');
		}
	}
	
	root.find('li').each(function() {
		var item = $(this);
		item.addClass('treeItem');
		
		var isFolder = item.find('ul,ol').size() > 0 || item.hasClass(folderClass);
		var imageHolder = $('<div>', { 'class': 'treeItemIcon ' + (isFolder ? 'treeFolder' : 'treeFile' )});
		
		item.prepend(imageHolder);
		
		imageHolder.click(function() {
			$(this).siblings('ul,ol').toggle();
			toggleState(this);
		});
	});
	
	jQuery(expandAll).click(function() {
		root.find('li').each(function() {
			$(this).find('ul,ol').show();
			toggleState($(this).find('div.treeItemIcon'), true);
		});
	});
	
	jQuery(collapseAll).click(function() {
		root.find('li').each(function() {
			$(this).find('ul,ol').hide();
			toggleState($(this).find('div.treeItemIcon'), false);
		});
	});
	
	return this;
}

