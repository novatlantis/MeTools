$(function() {
	$('textarea.wysiwyg').ckeditor({
		disableNativeSpellChecker: false,
		fontSize_sizes: '10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;30/30px;',
		height: 375,
		insertpre_class: false,
		language: 'it',
		removeButtons: 'Font',
		removeDialogTabs: false,
		tabSpaces: 4,
		toolbarCanCollapse: true,
		toolbarGroups: [
			{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
			{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
			{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
			{ name: 'links' },
			{ name: 'insert' },
			{ name: 'forms' },
			{ name: 'tools' },
			{ name: 'others' },
			'/',
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
			{ name: 'styles' },
			{ name: 'colors' },
			{ name: 'about' }
		],
		
		/**
		 * To use KCFinder, you have to comment out these lines and indicate the position of KCFinder
		 */
		//filebrowserBrowseUrl: 'kcfinder/browse.php?type=files',
		//filebrowserImageBrowseUrl: 'kcfinder/browse.php?type=images',
		//filebrowserFlashBrowseUrl: 'kcfinder/browse.php?type=flash',
		//filebrowserUploadUrl: 'kcfinder/upload.php?type=files',
		//filebrowserImageUploadUrl: 'kcfinder/upload.php?type=images',
		//filebrowserFlashUploadUrl: 'kcfinder/upload.php?type=flash',
	});
});