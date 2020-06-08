var relatedPages ={
	init:function(){
		this.blockForm = document.forms['ccm-block-form'];
		this.cParentIDRadios = this.blockForm.cParentID;
		for(var i=0; i < this.cParentIDRadios.length; i++){
			this.cParentIDRadios[i].onclick  = function(){ relatedPages.locationOtherShown(); }
			this.cParentIDRadios[i].onchange = function(){ relatedPages.locationOtherShown(); }			
		}
		
		this.truncateSwitch=$('#ccm-relatedpages-truncateSummariesOn');
		this.truncateSwitch.click(function(){ relatedPages.truncationShown(this); });
		this.truncateSwitch.change(function(){ relatedPages.truncationShown(this); });
	},	
	truncationShown:function(cb){ 
		var truncateTxt=$('#ccm-relatedpages-truncateTxt');
		var f=$('#ccm-relatedpages-truncateChars');
		if(cb.checked){
			truncateTxt.removeClass('faintText');
			f.attr('disabled',false);
		}else{
			truncateTxt.addClass('faintText');
			f.attr('disabled',true);
		}
	},
	locationOtherShown:function(){
		for(var i=0; i < this.cParentIDRadios.length; i++){
			if( this.cParentIDRadios[i].checked && this.cParentIDRadios[i].value =='OTHER' ){
				$('div.ccm-page-list-page-other').css('display','block');
				return; 
			}				
		}
		$('div.ccm-page-list-page-other').css('display','none');
	},
	validate:function(){
			var failed=0;
			
			var rssOn=$('#ccm-relatedpages-rssSelectorOn');
			var rssTitle=$('#ccm-relatedpages-rssTitle');
			if( rssOn && rssOn.attr('checked') && rssTitle && rssTitle.val().length==0 ){
				alert(ccm_t('feed-name'));
				rssTitle.focus();
				failed=1;
			}
			
			if(failed){
				ccm_isBlockError=1;
				return false;
			}
			return true;
	}	
}
Concrete.event.bind('problog_block_edit.problog', function() { 
	relatedPages.init();
});

ccmValidateBlockForm = function() { return relatedPages.validate(); }