<script type="text/javascript" charset="utf-8">
    var datatableConfig = {
        "name": "posts",
        "columns": [
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
                "width": '10%'
            }
        ],
        "search": {
            'active': true,
            "mode": 'remote',
            'url': "<?php echo Router::url('/', true); ?>posts/ajax_fetch", //Required if mode is remote
        },
        "order": {
            "mode": 'remote',
            "url": "<?php echo Router::url('/', true); ?>posts/ajax_fetch"
        },
        "remove": {
            "active": true,
            "withEdit": true,
            "mode": 'remote',
            "removeConMessage": 'Are you sure you want to lock it',
            "formSubmitAction": '<?php echo Router::url('/', true); ?>posts/delete',
            "submitMethod": 'POST',
            "callback ": function (datatable, errorsNumber) {
            }
        },
        "add": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>posts/add'
        },
        "editSingle": {
            "active": '<?php echo $editAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>posts/edit'
        },
        "deleteSingle": {
            "active": '<?php echo $deleteAccess; ?>',
            "removeConMessage": 'Are you sure you want to lock it',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>posts/delete'
        },
        "exportDATA": {
            "action": '<?php echo Router::url('/', true); ?>posts/export_fetch',
            "active": false, //Active or not
            "showButton": true, //Show the export button in the toolbar
            "delimiter": ";"//Set the delimiter
        },
        "advancefilter": {
            "active": true, // TO MAKE IT ACTIVE
            "showButton": true, // TO SHOW BUTTON
            "action": '<?php echo Router::url('/', true); ?>posts/ajax_fetch', // ACTION
            "fields": {
                'Post.subject': 'Subject',
                'Post.status': 'Status',
            } //FIELDS AND RELEVANT NAME FOR COLUMN
        },
        "url": "<?php echo Router::url('/', true); ?>posts/ajax_fetch"
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
