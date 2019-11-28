CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] }
	];

	config.removeButtons = 'Save,NewPage,Preview,Templates,Cut,Copy,Undo,Redo,Find,Replace,SelectAll,CopyFormatting,RemoveFormat,Language,BidiRtl,BidiLtr,Anchor,Flash,Smiley,Styles,Paste,PasteFromWord,Form,Checkbox,Radio,TextField,Textarea,Select,Button,HiddenField,Print,Strike,PageBreak,ShowBlocks';

};