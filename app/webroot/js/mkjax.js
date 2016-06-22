/**
 * 
 * Super Ajax Script Written By Manish Jangir
 * 
 * @type @exp;window@pro;setInterval
 */
//Copyright 2012, etc.

var Mkjax = function() {};
var MkjaxResponseHandler = function () {};
var mkjIntervalVar;
var mkjTimeoutVar;

//Main prototype to make an ajax request

Mkjax.prototype._ajax = function ($element, url, method, data) {
    $element.trigger('mkjax:begin', [$element]);
    var newData = $element.triggerHandler('mkjax:modify-data', data);
    var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
    var processData = true;
    var cache = true;
    var useFormData = typeof data === "object";

    if (newData) {
        data = newData;
    }
    if (useFormData) {
        contentType = false;
        processData = false;
        cache = false;
    }
    $.ajax({
        url         : url,
        type        : method,
        dataType    : 'json',
        data        : data,
        cache       : cache,
        processData : processData,
        contentType : contentType,
        headers: {'X-Mkjax': true},
        beforeSend: function (){
            Mkjax.prototype.ajaxLoader($element);
        }
    }).done( function (responseData, textStatus, jqXHR) {
        if (!responseData) {
            responseData = {};
        }
        $element.trigger('mkjax:success', [$element, responseData, textStatus, jqXHR]);
    }).fail( function (jqXHR, textStatus, errorThrown) {
        $element.trigger('mkjax:error', [$element, jqXHR, textStatus, errorThrown]);
    }).always( function (responseData, textStatus, jqXHR) {
        $($element).LoadingOverlay("hide", true);
        $.LoadingOverlay("hide");
        $(document).trigger('mkjax:complete', [$element, responseData, textStatus, jqXHR]);
    });
};


Mkjax.prototype._ajaxsubmit = function($form) {
    var options = { 
        beforeSubmit:  Mkjax.prototype.beforeFormSubmit,  // pre-submit callback 
        success:       Mkjax.prototype.afterFormSubmit  // post-submit callback 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
    $form.ajaxSubmit(options);
    return false;
};

Mkjax.prototype.ajaxLoader = function($element) {
    if($element.attr('data-mkjaxloader') || $element.data('mkjaxloader')) {
        var loverlayConf = {
            color           : ($element.attr('data-mkjaxloader-color')) ? $element.attr('data-mkjaxloader-color') : "rgba(255, 255, 255, 0.8)",  // String
            image           : ($element.attr('data-mkjaxloader-image')) ? $element.attr('data-mkjaxloader-image') : "../img/loading.gif",               // String
            maxSize         : ($element.attr('data-mkjaxloader-maxSize')) ? $element.attr('data-mkjaxloader-maxSize') : "50px",                     // Integer/String
            minSize         : ($element.attr('data-mkjaxloader-minSize')) ? $element.attr('data-mkjaxloader-minSize') : "50px",                      // Integer/String
            resizeInterval  : 0,
            size            : "50%"
        };
        if($element.attr('data-mkjaxloader-only') && $element.attr('data-mkjaxloader-only') === 'true') {
            $element.LoadingOverlay("show",loverlayConf);
        } else {
            $.LoadingOverlay("show",loverlayConf);
        }
    }
};

Mkjax.prototype.beforeFormSubmit = function(formData, jqForm, options) {
    $(jqForm).trigger('mkjax:begin', [$(jqForm)]);
    Mkjax.prototype.ajaxLoader(jqForm);
    //var queryString = $.param(formData); 
    return true; 
};
 
Mkjax.prototype.afterFormSubmit = function(responseData, textStatus, jqXHR, $element)  {
    $($element).LoadingOverlay("hide", true);
    $.LoadingOverlay("hide");
    if (!responseData) {
        responseData = {};
    }
    if(jqXHR.status === 200) {
        $element.trigger('mkjax:success', [$element, responseData, textStatus, jqXHR]);
    } else {
        $element.trigger('mkjax:error', [$element, jqXHR, textStatus, jqXHR.statusText]);
    }
};
    
//If a link is clicked through ajax
Mkjax.prototype.click = function (e) {
    var $this       = $(this);
    var url         = $this.attr('href');
    var method      = $this.data('method');
    var data        = $this.data('data');
    var dataObj     = null;
    var keyval      = null;
    var confirmMsg  = $this.data('confirm');
    
    if($this.attr('disabled') || $this.is(':disabled')) {
        return false;
    }

    if (typeof method === 'undefined' || !method) {
        method = 'GET';
    }

    if (data) {
        dataObj = {};
        data.split(',').map(
            function(kv) {
                keyval = kv.split(':');
                if (keyval[1].indexOf('#') === 0) {
                    dataObj[keyval[0]] = $(keyval[1]).val();
                } else {
                    dataObj[keyval[0]] = keyval[1];
                }
            }
        );
    }

    e.preventDefault();

    /*if(confirmMsg) {
        (new PNotify({
            title: 'Confirmation',
            text: confirmMsg,
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            type: 'info',
            confirm: {
                confirm: true
            },
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            }
        })).get().on('pnotify.confirm', function() {
            Mkjax.prototype._ajax($this, url, method, data);
        }).on('pnotify.cancel', function() {
            return false;
        });
    } else {
        Mkjax.prototype._ajax($this, url, method, data);
    }*/
    if(confirmMsg) {
        if(confirm(confirmMsg)) {
            Mkjax.prototype._ajax($this, url, method, data);
        } else {
            return false;
        }
    } else {
        Mkjax.prototype._ajax($this, url, method, data);
    }
    
};

//If a form is submitted through ajax
Mkjax.prototype.submit = function (e) {
    e.stopImmediatePropagation();
    var $this   = $(this);
    var confirmMsg  = $this.data('confirm');
    e.preventDefault();
    if($this.is(':disabled')) {
        return false;
    }
    if(confirmMsg) {
        if(confirm(confirmMsg)) {
            Mkjax.prototype._ajaxsubmit($this);
        } else {
            return false;
        }
    } else {
        Mkjax.prototype._ajaxsubmit($this);
    }
    
};

//If a timeout ajax is called
Mkjax.prototype.timeout = function (event, element) {
    var $element    = $(element);
    var timeout     = $element.data('timeout');
    var url         = $element.data('url');
    var method      = $element.data('method');

    if (typeof method === 'undefined' || !method) {
        method = 'GET';
    }

    window.setTimeout(Mkjax.prototype._ajax, timeout, $element, url, method, null);
};

//If an ajax request has to be called on particular interval
Mkjax.prototype.interval = function (event, element) {
    var $element    = $(element);
    var interval    = $element.data('interval');
    var url         = $element.data('url');
    var method      = $element.data('method');

    if (typeof method === 'undefined' || !method) {
        method = 'GET';
    }

    window.setInterval(Mkjax.prototype._ajax, interval, $element, url, method, null);
};

//Live event binding
$(function () {
    $('body').on('click', 'a.mkjax', Mkjax.prototype.click);
    $('body').on('submit', 'form.mkjax', Mkjax.prototype.submit);
    //$('body').on('click', 'a[data-cancel-closest]', Mkjax.prototype.cancel);

    $('[data-timeout]').each(Mkjax.prototype.timeout);
    $('[data-interval]').each(Mkjax.prototype.interval);
});


MkjaxResponseHandler.prototype.global = function(event, $element, response,textStatus, jqXHR) {
    //If the response has notifications to show
    try {
        response = $.parseJSON(response);
    } catch (e) {
        response = response;
    }
    if(response.notification) {
        $.each(response.notification, function(key, notification) {
            if(notification.type === 'pnotify') {
                var pnfDefConf = {
                    title                   : false,
                    title_escape            : true,
                    text                    : false,
                    text_escape             : false,
                    styling                 : "bootstrap3",
                    auto_display            : true,
                    type                    : (notification.status && (notification.status === 'success' || notification.status === 'info' || notification.status === 'notice' || notification.status === 'error')) ? notification.status : 'notice',
                    icon                    : true,
                    animation               : "fade",
                    animate_speed           : "slow",
                    position_animate_speed  : 500,
                    opacity                 : 1,
                    shadow                  : true,
                    hide                    : (notification.autoHide === false) ? false : true,
                    delay                   : (notification.hideDelay && $.isNumeric(notification.hideDelay)) ? notification.hideDelay : 4000,
                    remove                  : true,
                    insert_brs              : true
                };
               pnfDefConf = $.extend({}, pnfDefConf, {text:notification.message});
               if(notification.title && notification.title != '') {
                   pnfDefConf['title'] = notification.title;
               }
               new PNotify(pnfDefConf); 
            } else if(notification.type === 'alert') {
                var alertType = notification.status;
                if(notification.status === 'notice') {
                    alertType = 'warning';
                } else if(notification.status === 'error') {
                    alertType = 'danger';
                }
                var $alertHtml = $($.parseHTML('<div class="mkjax-alert alert alert-'+alertType+'"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'+notification.title+'</strong> '+notification.message+'</div>'));
                var wrapper   = (notification.wrapper) ? notification.wrapper : '.mkjax-alert-wrapper';
                $(wrapper).find('.alert-'+alertType).remove();
                $(wrapper).append($alertHtml);
                if(notification.hideDelay && $.isNumeric(notification.hideDelay) && notification.autoHide !== false) {
                    $alertHtml.fadeOut(notification.hideDelay, function(){
                        $alertHtml.remove();
                    });
                }
            }
        });
    }
    
    
    //If Location is provided in response
    if(response.redirect) {
        if(response.redirect.additional) {
            $.each(response.redirect.additional, function(k, l){
                var awto = (l.after && $.isNumeric(l.after)) ? l.after : 0;
                setTimeout(function(){
                    window.open(l.url, '_blank');
                }, awto);
            });
        }
        if(response.redirect.current) {
            if(response.redirect.current.url) {
                var redto = (response.redirect.current.after && $.isNumeric(response.redirect.current.after)) ? response.redirect.current.after : 0;
                if(response.redirect.current.url === 'refresh') {
                    setTimeout(function(){
                        window.location.reload(true);
                    }, redto);
                } else {
                    setTimeout(function(){
                        window.location.href = response.redirect.current.url;
                    }, redto);
                }
            }
            if(response.redirect.current.modal) {
                var mto = (response.redirect.current.modal.after && $.isNumeric(response.redirect.current.modal.after)) ? response.redirect.current.modal.after : 0;
                setTimeout(function(){
                    $("#mkjax-modal").modal({
                        remote: response.redirect.current.modal.url,
                        backdrop: response.redirect.current.modal.backdrop, 
                        keyboard: response.redirect.current.modal.keyboard
                    });
                }, mto);
            }
            if(response.redirect.current.popup) {
                $.each(response.redirect.current.popup, function(rrcp, pop){
                    var ppto = (pop.after && $.isNumeric(pop.after)) ? pop.after : 0;
                    setTimeout(function(){
                        var popwidth = (pop.width) ? pop.width : 400,
                            popheight= (pop.height) ? pop.height : 400,
                            poploc   = (pop.location) ? pop.location : 0,
                            popstatus= (pop.status) ? pop.status : 0,
                            popscroll= (pop.scrollbar) ? pop.scrollbar : 0;
                        var popstr = 'width='+popwidth+ ',height='+popheight+ ',location='+poploc+ ',status='+popstatus+ ',scrollbar='+popscroll;
                        mkjaxpopwin = window.open(pop.url, "_blank", popstr);
                        mkjaxpopwin.moveTo(0, 0);
                    }, mto);
                });
            }
        }
    }
    
    if(response.interval) {
        if(response.interval.url && response.interval.time) {
            var intervalMethod = (response.interval.method === 'GET') ? 'GET' : 'POST';
            mkjIntervalVar = window.setInterval(Mkjax.prototype._ajax, response.interval.time, $element, response.interval.url, intervalMethod, null);
        }
    }
    
    if(response.timeout) {
        if(response.timeout.url && response.timeout.time) {
            var timeoutMethod = (response.timeout.method === 'GET') ? 'GET' : 'POST';
            mkjTimeoutVar = window.setTimeout(Mkjax.prototype._ajax, response.timeout.time, $element, response.timeout.url, timeoutMethod, null);
        }
    }
    
    if(typeof response.evaljs !== 'undefined') {
        window[response.evaljs].apply(this);
    }
    
    if(response.clearinterval) {
        clearInterval(mkjIntervalVar);
    }
    
    if(response.cleartimeout) {
        clearTimeout(mkjTimeoutVar);
    }
    if(response.modalclose) {
        var mcto = (response.modalclose.timeout && $.isNumeric(response.modalclose.timeout)) ? response.modalclose.timeout : 0;
        setTimeout(
                function(){ 
                    $('.modal').modal('hide');
                }, mcto);
    }
};


MkjaxResponseHandler.prototype.validation	= function(e, $element, response) {
    try {
        response = $.parseJSON(response);
    } catch(err) {
        response = response;
    }
    if(response.validation) {
        var $form   = $(response.validation.form);
        var errors  = response.validation.errors;
        $.each(errors,function(i,v){
            $form.data('formValidation').updateMessage(i, 'notEmpty', v).updateStatus(i, 'INVALID', 'notEmpty');
        });
        $('input[type="submit"]').attr({disabled:false}).removeClass('disabled');
    }
}

//This event will be triggered manually in any function
MkjaxResponseHandler.prototype.timerAjax = function(event, $masterElement) {
	
}

//This event always triggered and checks if windows has to be redirect to a url
MkjaxResponseHandler.prototype.redirect = function(event, $element, response) {
    if (response.location) {
        window.location.href = response.location;
        return false;
    }
};

/**
 * This event replaces the whole dom element with new ajax response. 
 * The replacable dom id or class should be defined as data attribute on target dom object.
 *
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.replace = function(event, $element, data) {
    $($element.data('replace')).replaceWith(data.html);
};

/**
 * This event replaces the whole closest dom element to clicked object 
 * with new ajax response. The replacablec closest dom id or class should be defined as data attribute
 * on target dom object.
 *
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.replaceClosest = function(event, $element, data) {
    $element.closest($element.data('replace-closest')).replaceWith(data.html);
};

/**
 * This event replaces the inner HTML of a dom which is defined as data-replace-inner attribute
 * on target dom object.
 *
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.replaceInner = function(event, $element, data) {
    $($element.data('replace-inner')).html(data.html);
};

/**
 * This event replaces the inner HTML of the closest dom which is defined as data-replace-closest-inner
 * attribute on target dom object.
 *
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.replaceClosestInner = function(event, $element, data) {
    $element.closest($element.data('replace-closest-inner')).html(data.html);
};

/**
 * This event appends the ajax response to an appendable dom element which is defined as 
 * data-append attribute on target dom object.
 *
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.append = function(event, $element, data) {
    $($element.data('append')).append(data.html);
};

/**
 * This event prepends the ajax response to a dom element which is defined as 
 * data-prepend attribute on target dom object.
 *
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.prepend = function(event, $element, data) {
    $($element.data('prepend')).prepend(data.html);
};

/**
 * This event refreshes a dom element which is defined as data-refresh attribute
 * on target dom object. The refreshable dom must have a data-refresh-url attribute
 * to where we make an ajax request
 *
 * @param {type} event
 * @param {type} $element
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.refresh = function(event, $element) {
    $.each($($element.data('refresh')), function(index, refreshable) {
        $.getJSON($(refreshable).data('refresh-url'), function(data) {
            $(refreshable).replaceWith(data.html);
        });
    });
};

/**
 * This event refreshes the closest dom element which is defined as data-refresh-closest attribute
 * on target dom object. The refreshable dom must have a data-refresh-url attribute
 * to where we make an ajax request
 *
 * @param {type} event
 * @param {type} $element
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.refreshClosest = function(event, $element) {
    $.each($($element.data('refresh-closest')), function(index, refreshable) {
        $.getJSON($(refreshable).data('refresh-url'), function(data) {
            $element.closest($(refreshable)).replaceWith(data.html);
        });
    });
};

/**
 * If the target dom has a data-clear attribute refrensing another dom element.
 * The clearable dom will be made empty.
 * 
 * @param {type} event
 * @param {type} $element
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.clear = function(event, $element) {
    $($element.data('clear')).html('');
};

/**
 * If the target dom has a data-remove attribute refrensing another dom element.
 * The removable dom will be removed from dom tree.
 * 
 * @param {type} event
 * @param {type} $element
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.remove = function(event, $element) {
    $($element.data('remove')).remove();
};

/**
 * If the target dom has a data-clear-closest attribute refrensing another dom element.
 * The closest clearable dom will be made empty.
 * 
 * @param {type} event
 * @param {type} $element
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.clearClosest = function(event, $element) {
    $element.closest($element.data('clear-closest')).html('');
};

/**
 * If the target dom has a data-remove-closest attribute refrensing another dom element.
 * The closest removable dom will be removed from dom tree.
 * 
 * @param {type} event
 * @param {type} $element
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.removeClosest = function(event, $element) {
    $element.closest($element.data('remove-closest')).remove();
};

/**
 * We can have full control on replacing, appending, prepending something to some dom elements
 * @param {type} event
 * @param {type} $element
 * @param {type} data
 * @returns {undefined}
 */
MkjaxResponseHandler.prototype.fragments = function(event, $element, data) {
    if (data.fragments) {
        $.each(data.fragments, function (dom, html) {
            $(dom).replaceWith(html);
        });
    }
    if (data.fragments && data.fragments.innerfragments) {
        $.each(data.fragments.innerfragments, function(dom, html) {
            $(dom).html(html);
        });
    }
    if (data.fragments && data.fragments.appendfragments) {
        $.each(data.fragments.appendfragments, function(dom, html) {
            $(dom).append(html);
        });
    }
    if (data.fragments && data.fragments.prependfragments) {
        $.each(data.fragments.prependfragments, function(dom, html) {
            $(dom).prepend(html);
        });
    }
};


$(function () {
    $(document).on('mkjax:success', MkjaxResponseHandler.prototype.global);
    $(document).on('mkjax:success', MkjaxResponseHandler.prototype.validation);
    $(document).on('mkjax:success', MkjaxResponseHandler.prototype.redirect);
    $(document).on('mkjax:success', MkjaxResponseHandler.prototype.fragments);
    $(document).on('mkjax:success', '[data-replace]', MkjaxResponseHandler.prototype.replace);
    $(document).on('mkjax:success', '[data-replace-closest]', MkjaxResponseHandler.prototype.replaceClosest);
    $(document).on('mkjax:success', '[data-replace-inner]', MkjaxResponseHandler.prototype.replaceInner);
    $(document).on('mkjax:success', '[data-replace-closest-inner]', MkjaxResponseHandler.prototype.replaceClosestInner);
    $(document).on('mkjax:success', '[data-append]', MkjaxResponseHandler.prototype.append);
    $(document).on('mkjax:success', '[data-prepend]', MkjaxResponseHandler.prototype.prepend);
    $(document).on('mkjax:success', '[data-refresh]', MkjaxResponseHandler.prototype.refresh);
    $(document).on('mkjax:success', '[data-refresh-closest]', MkjaxResponseHandler.prototype.refreshClosest);
    $(document).on('mkjax:success', '[data-clear]', MkjaxResponseHandler.prototype.clear);
    $(document).on('mkjax:success', '[data-remove]', MkjaxResponseHandler.prototype.remove);
    $(document).on('mkjax:success', '[data-clear-closest]', MkjaxResponseHandler.prototype.clearClosest);
    $(document).on('mkjax:success', '[data-remove-closest]', MkjaxResponseHandler.prototype.removeClosest);
    $(document).on('mkjax:timerajax', 'body', MkjaxResponseHandler.prototype.timerAjax);
    
    var mkjaxmodal = '<div id="mkjax-modal" class="modal fade">';
        mkjaxmodal +='<div class="modal-dialog modal-lg">';
        mkjaxmodal +='<div class="modal-content">';
        mkjaxmodal +='</div>';
        mkjaxmodal +='</div>';
        mkjaxmodal +='</div>';
    $('body').append(mkjaxmodal);
});
