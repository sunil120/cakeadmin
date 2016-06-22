//Menu Permissions Javascript
$(document).on('change', 'input.child-link-check-action', function () {
    $parentDiv = $(this).parents('div.child-link');
    var checkedLength = $parentDiv.find('input.child-link-check-action:checked').length;
    var allLength = $parentDiv.find('input.child-link-check-action').length;
    var status = (checkedLength == allLength) ? true : false;
    $parentDiv.find('input.child-link-check-all').prop({checked: status});
    
    $superParent = $(this).parents('div.parent-link');
    var parentChecked = $superParent.find('.child-link-check-all:checked').length;
    var parentAll = $superParent.find('.child-link-check-all').length;
    if(parentChecked === parentAll) {
        $superParent.find('.check-all-submodules').prop({checked:true});
    } else {
        $superParent.find('.check-all-submodules').prop({checked:false});
    }
});

$(document).on('change', 'div.child-link input.child-link-check-all', function () {
    var status = this.checked;
    $superParent = $(this).parents('div.parent-link');
    $parentDiv = $(this).parents('div.child-link');
    $parentDiv.find('input.child-link-check-action').prop({checked: status});
    if($superParent.find('input.child-link-check-all:checked').length != $superParent.find('input.child-link-check-all').length) {
        $superParent.find('input.check-all-submodules').prop({checked: false});
    } else {
        $superParent.find('input.check-all-submodules').prop({checked: true});
    }
});

$(document).on('change', 'input.check-all-submodules', function () {
    $superParent = $(this).parents('div.parent-link');
    if(this.checked) {
        $superParent.find('input.parent-link-check-all').prop({disabled:false}).val('ALL');
        $superParent.find('.child-links input[type="checkbox"]').prop({checked: true});
    } else {
        $superParent.find('input.parent-link-check-all').prop({disabled:true}).val('');
        $superParent.find('.child-links input[type="checkbox"]').prop({checked: false});
    }
});

$(document).on('change', 'div.child-links input[type="checkbox"]', function () {
    var $superChildDiv = $(this).parents('div.child-links');
    var $superParentDiv = $superChildDiv.parents('div.parent-link');
    var checkedLength = $superChildDiv.find('input[type="checkbox"]:checked').length;
    var status = (checkedLength > 0) ? true : false;
    if(status) {
        $superParentDiv.find('input.parent-link-check-all').prop({disabled:false}).val('ALL');
    } else {
        $superParentDiv.find('input.parent-link-check-all').prop({disabled:true}).val('');
    }
});

$(document).on('change','div.menu-action input[type="checkbox"]', function(){
    var $parentDiv = $(this).parents('div.child-link');
    if(this.checked) {
        $parentDiv.find('input[type="checkbox"][value="VIEW"]').prop({checked: true});
    }
});

$(document).on('change', '#select-deselect-all-modules', function(){
    if(this.checked) {
        $('.parent-link-check-all').prop({disabled:false}).val('ALL');
        $('#updateModulePermissions').find('input[type="checkbox"]').not('.exclude-bulk-check').prop({checked: true});
    } else {
        $('.parent-link-check-all').prop({disabled:true}).val('');
        $('#updateModulePermissions').find('input[type="checkbox"]').not('.exclude-bulk-check').prop({checked: false});
    }
});

$(document).on('change', '#expand-collapse-all-modules', function(){
    var $this = $(this);
    $('#accordion .parent-link').each(function(){
        if($this.is(':checked')) {
            $('#accordion .parent-link').collapse('show');
        } else {
            $('#accordion .parent-link').collapse('hide');
        }
    });
});

$(document).ready(function(){
   $('.check-all-submodules').each(function(){
       $superParent = $(this).parents('div.parent-link');
        if($superParent.find('.child-link-check-all:checked').length === $superParent.find('.child-link-check-all').length) {
            $(this).prop({checked: true});
        } else {
            $(this).prop({checked: false});
        }
   });
   if($("#updateModulePermissions .panel-body input:checkbox:not(:checked)").length <= 0) {
       $('#select-deselect-all-modules').prop({checked: true});
   }
   
   $("div.parent-link").each(function (index) {
        var childDiv = $(this).find("div.child-links");
        var checkedLength = childDiv.find('input[type="checkbox"]:checked').length;
        if (checkedLength > 0)
            $(this).find('input.parent-link-check-all').prop({disabled: false}).val('ALL');
        else
            $(this).find('input.parent-link-check-all').prop({disabled: true}).val('');
    });
});
