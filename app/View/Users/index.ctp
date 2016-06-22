<script type="text/javascript" charset="utf-8">

    var datatableConfig = {
        "name": "users",
        "columns": [
            {
                "header": "User Name",
                "property": "user_name",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": true,
                "edit": true,
                "hide": true,
                "width": '10%'
            },
            {
                "header": "Email ID",
                "property": "email",
                "order": true,
                "edit": true,
                "type": "text",
                "showFilter": false,
                "hide": true,
                "width": '10%'
            },
            {
                "header": "Full Name",
                "property": "full_name",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": false,
                "edit": true,
                "hide": true,
                "width": '15%'
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
                "width": '8%'
            },
            {
                "header": "Role",
                "property": "RoleMaster_role_name",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": false,
                "edit": true,
                "hide": true,
                "width": '10%'
            },
            ],
        "search": {
            'active': true,
            "mode": 'remote',
            'url': "<?php echo Router::url('/', true); ?>users/ajax_fetch", //Required if mode is remote
        },
        "order": {
            "mode": 'remote',
            "url": "<?php echo Router::url('/', true); ?>users/ajax_fetch"
        },
        "remove": {
            "active": true,
            "withEdit": true,
            "mode": 'remote',
            "formSubmitAction": '<?php echo Router::url('/', true); ?>users/delete',
            "submitMethod": 'POST',
            "callback ": function (datatable, errorsNumber) {
            }
        },
        "add": {
            "active": true,
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>users/add'
        },
        "editSingle": {
            "active": '<?php echo $editAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>users/edit'
        },
        "deleteSingle": {
            "active": '<?php echo $deleteAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>users/delete'
        },
        "cancel": {
            "active": false, //Active or not
            "showButton": false//Show the cancel button in the toolbar
        },
        "advancefilter": {
            "active": true, // TO MAKE IT ACTIVE
            "showButton": true, // TO SHOW BUTTON
            "action": '<?php echo Router::url('/', true); ?>users/ajax_fetch', // ACTION
            "fields": {
                'UserModel.user_name': 'User Name',
                'UserModel.email': 'Email Id',
                'UserModel.first_name': 'First Name',
                'UserModel.middle_name': 'Middle Name',
                'UserModel.last_name': 'Last Name',
                'RoleMaster.role_name': 'Role',
                'UserModel.status': 'Status',
            } //FIELDS AND RELEVANT NAME FOR COLUMN
        },
        "url": "<?php echo Router::url('/', true); ?>users/ajax_fetch"
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
