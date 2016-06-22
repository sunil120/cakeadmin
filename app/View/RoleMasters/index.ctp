<script type="text/javascript" charset="utf-8">

    var datatableConfig = {
        "name": "users",
        "columns": [
            {
                "header": "Role Name",
                "property": "role_name",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": true,
                "edit": false,
                "hide": true,
                "width": '20%'
            },
            {
                "header": "Description",
                "property": "description",
                "edit": false,
                "type": "text",
                "showFilter": true,
                "hide": true,
                "width": '50%'
            },
            {
                "header": "Status",
                "property": "status",
                "order": true,
                "group": true,
                "choiceInList": true,
                "listStyle": "select",
                "possibleValues": [
                    {code: '', name: 'Select'},
                    {code: 'Active', name: 'Active'},
                    {code: 'Inactive', name: 'Inactive'}
                ],
                "type": "text",
                "showFilter": false,
                "edit": true,
                "hide": true,
            },
        ],
        "search": {
            'active': true,
            "mode": 'remote',
            'url': "<?php echo Router::url('/', true); ?>roleMasters/ajax_fetch", //Required if mode is remote
        },
        "order": {
            "mode": 'remote',
            "url": "<?php echo Router::url('/', true); ?>roleMasters/ajax_fetch"
        },
        "remove": {
            "active": true,
            "withEdit": true,
            "mode": 'remote',
            "formSubmitAction": '<?php echo Router::url('/', true); ?>roleMasters/delete',
            "submitMethod": 'POST',
            "callback ": function (datatable, errorsNumber) {
            }
        },
        "add": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>roleMasters/add'
        },
        "editSingle": {
            "active": '<?php echo $editAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>roleMasters/edit'
        },
        "deleteSingle": {
            "active": '<?php echo $deleteAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>roleMasters/delete'
        },
        "advancefilter": {
            "active": true, // TO MAKE IT ACTIVE
            "showButton": true, // TO SHOW BUTTON
            "action": '<?php echo Router::url('/', true); ?>roleMasters/ajax_fetch', // ACTION
            "fields": {
                'RoleMaster.role_name': 'Role Name',
                'RoleMaster.description': 'Description',
                'RoleMaster.status': 'Status',
            } //FIELDS AND RELEVANT NAME FOR COLUMN
        },
        "cancel": {
            "active": false, //Active or not
            "showButton": false//Show the cancel button in the toolbar
        },
        "url": "<?php echo Router::url('/', true); ?>roleMasters/ajax_fetch"
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