<script type="text/javascript" charset="utf-8">

    var datatableConfig = {
        "name": "state_masters",
        "columns": [
            {
                "header": "Country",
                "property": "country_name",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": false,
                "edit": true,
                "hide": true,
            },
            {
                "header": "State",
                "property": "state_name",
                "order": true,
                "group": true,
                "type": "text",
                "showFilter": false,
                "edit": true,
                "hide": true,
            },
        ],
        "search": {
            'active': true,
            "mode": 'remote',
            'url': "<?php echo Router::url('/', true); ?>state_masters/ajax_fetch", //Required if mode is remote
        },
        "order": {
            "mode": 'remote',
            "url": "<?php echo Router::url('/', true); ?>state_masters/ajax_fetch"
        },
        "remove": {
            "active": true,
            "withEdit": true,
            "mode": 'remote',
            "formSubmitAction": '<?php echo Router::url('/', true); ?>state_masters/delete',
            "submitMethod": 'POST',
            "callback ": function (datatable, errorsNumber) {
            }
        },
        "add": {
            "active": '<?php echo $addAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>state_masters/add'
        },
        "editSingle": {
            "active": '<?php echo $editAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>state_masters/edit'
        },
        "deleteSingle": {
            "active": '<?php echo $deleteAccess; ?>',
            "showButton": true,
            "action": '<?php echo Router::url('/', true); ?>state_masters/delete'
        },
        "exportDATA": {
            "action": '<?php echo Router::url('/', true); ?>state_masters/export_fetch',
            "active": '<?php echo @$exportAccess; ?>',
            "showButton": false, //Show the export button in the toolbar
            "delimiter": ";"//Set the delimiter
        },
        "cancel": {
            "active": false, //Active or not
            "showButton": false//Show the cancel button in the toolbar
        },
        "advancefilter": {
            "active": true, // TO MAKE IT ACTIVE
            "showButton": true, // TO SHOW BUTTON
            "action": '<?php echo Router::url('/', true); ?>state_masters/ajax_fetch', // ACTION
            "fields": {
                'CountryMaster.country_name': 'Country',
                'StateMaster.state_name': 'State',
            } //FIELDS AND RELEVANT NAME FOR COLUMN
        },
        "url": "<?php echo Router::url('/', true); ?>state_masters/ajax_fetch"
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