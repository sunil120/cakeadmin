<script type="text/javascript" charset="utf-8">

    var datatableConfig = {
        "name": "pages",
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
            }
        ],
        "search": {
            'active': true,
            "mode": 'remote',
            'url': "<?php echo Router::url('/', true); ?>pages/ajax_fetch", //Required if mode is remote
        },
        "order": {
            "mode": 'remote',
            "url": "<?php echo Router::url('/', true); ?>pages/ajax_fetch"
        },
        "remove": {
            "active": true,
            "withEdit": true,
            "mode": 'remote',
            "formSubmitAction": '<?php echo Router::url('/', true); ?>pages/delete',
            "submitMethod": 'POST',
            "callback ": function (datatable, errorsNumber) {
            }
        },
        "add": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>pages/add'
        },
        "editSingle": {
            "active": '<?php echo $editAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>pages/edit'
        },
        "deleteSingle": {
            "active": '<?php echo $deleteAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>pages/delete'
        },
        "exportDATA": {
            "action": '<?php echo Router::url('/', true); ?>pages/export_fetch',
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
            "action": '<?php echo Router::url('/', true); ?>pages/ajax_fetch', // ACTION
            "fields": {
                'Page.title': 'Title',
                'Page.status': 'Status',
            } //FIELDS AND RELEVANT NAME FOR COLUMN
        },
        "url": "<?php echo Router::url('/', true); ?>pages/ajax_fetch"
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
