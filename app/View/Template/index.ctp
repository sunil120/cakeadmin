<script type="text/javascript" charset="utf-8">

    var datatableConfig = {
        "name": "template",
        "columns": [
            {
                "header": "Title",
                "property": "title",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": true,
                "edit": true,
                "hide": true,
                "width": '40%'
            },
            {
                "header": "Subject",
                "property": "subject",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": true,
                "edit": true,
                "hide": true,
                "width": '20%'
            },
            {
                "header": "Status",
                "property": "status_text",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": true,
                "edit": true,
                "hide": true,
                "width": '10%',                
            },
        ],
        "actions": {
            "active": true,
            "canEdit": true,
            "canDelete": true,
            "canView": true
        },
        "edit": {
            "active": false,
            "columnMode": false
        },
        "pagination": {
            "mode": 'remote',
            "numberPageListMax": 3,
            "numberRecordsPerPage": 10,
        },
        "filter": {
            "active": true,
            "highlight": false,
            "columnMode": true,
        },
        "search": {
            'active': true,
            "mode": 'remote',
            'url': "<?php echo Router::url('/', true); ?>template/ajax_fetch", //Required if mode is remote
        },
        "order": {
            "mode": 'remote',
            "url": "<?php echo Router::url('/', true); ?>template/ajax_fetch"
        },
        "remove": {
            "active": true,
            "withEdit": true,
            "mode": 'remote',
            "formSubmitAction": '<?php echo Router::url('/', true); ?>template/delete',
            "submitMethod": 'POST',
            "callback ": function (datatable, errorsNumber) {
            }
        },
        "add": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>template/add'
        },
        "editSingle": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>template/edit'
        },
        "deleteSingle": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>template/delete'
        },
        "group": {
            "active": false,
        },
        "compact": true,
        "exportDATA": {
            "action": '<?php echo Router::url('/', true); ?>template/export_fetch',
            "active": false, //Active or not
            "showButton": true, //Show the export button in the toolbar
            "delimiter": ";"//Set the delimiter
        },
        "cancel": {
            "active": false, //Active or not
            "showButton": false//Show the cancel button in the toolbar
        },
        "advancefilter": {
            "active": true, // TO MAKE IT ACTIVE
            "showButton": true, // TO SHOW BUTTON
            "action": '<?php echo Router::url('/', true); ?>template/ajax_fetch', // ACTION
            "fields": {
                'Template.title': 'Title',
                'Template.type': 'Type',
                'Template.status': 'Default',
            } //FIELDS AND RELEVANT NAME FOR COLUMN
        },
        "url": "<?php echo Router::url('/', true); ?>template/ajax_fetch"
    };
    $.extend(datatableConfig, datatableConfigDefault);
</script>
<div>
    <div class="container-fluid">
        <div class="row">

            <div id="mainController">
                <div ng-controller="ngAppDemoController">
                    <div class="col-md-12 col-lg-12" ultimate-datatable="datatable"></div>
                </div>
            </div>
        </div>
    </div>
</div>
