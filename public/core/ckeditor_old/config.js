/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function( config )
{
	
	
	config.plugins = 'dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,undo,wsc,autogrow,backgrounds,lineutils,widget,btbutton,btgrid,footnotes,pbckcode,cleanuploader,codesnippet,cssanim,dropdownmenumanager,filetools,htmlbuttons,imagepaste,imageuploader,notification,notificationaggregator,uploadwidget,uploadimage,uicolor,widgettemplatemenu';
	config.skin = 'bootstrapck';
	
	
	// Define changes to default configuration here. For example:
	config.toolbarCanCollapse = true,
	config.enterMode = CKEDITOR.ENTER_P,
	config.shiftEnterMode = CKEDITOR.ENTER_BR,
	config.colorButton_enableMore = true,
  	config.bodyId = 'content',
	config.entities = false,
	config.forceSimpleAmpersand = false,
	config.fontSize_defaultLabel = '12px',
	config.font_defaultLabel = 'Arial',
	config.emailProtection = 'encode',
	config.contentsLangDirection = 'ltr',
	config.language = 'en',
	config.contentsLanguage = 'en',
	config.toolbarLocation = 'top',
	config.browserContextMenuOnCtrl = false,
	config.image_previewText = CKEDITOR.tools.repeat('Get-Simple - the best CMS for your purposes. Install it, test it, enjoy it. Free, OpenSource and userfriendly', 50 )
		};
// from  http://help.pixelandtonic.com/brandonkelly/topics/how_do_i_set_output_formatting_writer_rules?from_gsfn=true 
CKEDITOR.on( 'instanceReady', function( ev ) {

var blockTags = ['div','h1','h2','h3','h4','h5','h6','p','pre','ul','li'];
var rules = {
indent : false,
breakBeforeOpen : false,
breakAfterOpen : false,
breakBeforeClose : false,
breakAfterClose : true
};

for (var i=0; i<blockTags.length; i++) {
ev.editor.dataProcessor.writer.setRules( blockTags[i], rules );
}

});

// from  http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Dialog_Customization

CKEDITOR.on( 'dialogDefinition', function( ev )
	{
		// Take the dialog name and its definition from the event data.
		var dialogName = ev.data.name;
		var dialogDefinition = ev.data.definition;
 
		// Check if the definition is from the dialog we're
		// interested on (the Link dialog).
		if ( dialogName == 'link' )
		{
			/* Enable this part only if you don't remove the 'target' tab in the previous block. */
 			FCKConfig.DefaultLinkTarget = '_blank'
			// Get a reference to the "Target" tab.
			var targetTab = dialogDefinition.getContents( 'target' );
			var targetField = targetTab.get( 'linkTargetType' );
			targetField[ 'default' ] = '';
			linkField[ 'default' ] = 'URL';

		} // end dialogDefinition
 
		if ( dialogName == 'image' )
		{
		 dialogDefinition.removeContents( 'advanced' );
		dialogDefinition.removeContents( 'Link' );
		}
 
		if ( dialogName == 'flash' )
		{
					dialogDefinition.removeContents( 'advanced' );
		}
 
	});