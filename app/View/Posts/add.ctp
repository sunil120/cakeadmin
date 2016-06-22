<?php echo $this->CustomForm->create('Post', array('novalidate')); ?>
<div class="clearfix" ng-app="plunker">
    <div class="col-lg-9">
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Subject</label>
            <div class="col-md-9">
                <?php
                echo $this->CustomForm->input('subject');
                ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for=""><span class="error-text">*</span>Content</label>
            <div class="col-md-9">
                <?php echo $this->CustomForm->input('content' , array('type'=>'textarea','class'=>'textarea')); ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for="">
                Tags
            </label>
            <div class="col-md-9" ng-controller="TagController">
                <tags-input class="tag-autocomplete" ng-model="tags" add-on-paste="true" name="abc" bind-internal-input-to="data[TagMaster][tag_name]">
                    <auto-complete source="loadTags($query)"></auto-complete>
                </tags-input>
                <input type='text' class='form-control' value='{{tags}}' name='data[TagMaster][tag_name]' style='display: none'/>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3 control-label" for="">Status</label>
            <div class="col-md-6 radio-inline status-radio" id="radio">
                    <?php
                    echo $this->CustomForm->radio('status', array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                        ), array(
                        'id' => 'radio1',
                        'value'=>1,
                        'legend' => false,
                    ));
                    ?>
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-3   control-label" for=""></label>
            <div class="col-md-9">
                <div class="button-section clearfix">
                    <?php echo $this->CustomForm->button('Submit', array('class' => 'btn-primary')); ?>
                    <a class="btn btn-flat btn-default"
                       href="<?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'index')); ?>"
                       title="Cancel">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Preview</h4>
            </div>
            <div class="modal-body">
                <div id="FaqAnswer">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-flat" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->CustomForm->end(); ?>
<script>
    var moduleTag = angular.module('plunker', ['ngTagsInput']);
    moduleTag.controller('TagController', function ($scope, $http) {
        $scope.tags = '';
        $scope.loadTags = function (query) {
            return $http.get('<?php echo Router::url('/', true); ?>tag_masters/autocompleteTag?tag=' + query);
        };
    });
</script>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('PostContent');
</script>

