$(document).ready(function () {


    //Transform Tabs Start
    var Tabs = {
        init: function () {
            this.bindUIfunctions();
            this.pageLoadCorrectTab();
        },
        bindUIfunctions: function () {

            // Delegation
            $(document)
                    .on("click", ".transformer-tabs a[href^='#']:not('.active')", function (event) {
                        Tabs.changeTab(this.hash);
                        event.preventDefault();
                    })
                    .on("click", ".transformer-tabs a.active", function (event) {
                        Tabs.toggleMobileMenu(event, this);
                        event.preventDefault();
                    });

        },
        changeTab: function (hash) {

            var anchor = $("[href='" + hash + "']");
            var div = $(hash);

            // activate correct anchor (visually)
            anchor.addClass("active").parent().siblings().find("a").removeClass("active");

            // activate correct div (visually)
            div.addClass("active").siblings().removeClass("active");

            // update URL, no history addition
            // You'd have this active in a real situation, but it causes issues in an <iframe> (like here on CodePen) in Firefox. So commenting out.
            // window.history.replaceState("", "", hash);

            // Close menu, in case mobile
            anchor.closest("ul").removeClass("open");

        },
        // If the page has a hash on load, go to that tab
        pageLoadCorrectTab: function () {
            this.changeTab(document.location.hash);
        },
        toggleMobileMenu: function (event, el) {
            $(el).closest("ul").toggleClass("open");
        }

    }

    Tabs.init();



    $(window).resize(function () {
        if ($(window).width() > 767)
        {
            var WindowHeight = $(window).height();
            $('.login-wrapper').height(WindowHeight);
        }
        else {
            $('.login-wrapper').css('height', '');
        }

    });
    $(window).resize();

    //Navigation Slider start
    if ($('.nav-slider-tabs').length > 0) {
        $('.nav-slider-tabs').owlCarousel({
            loop: true,
            margin: 14,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: false
                },
                1000: {
                    items: 6,
                    nav: true,
                    loop: false
                }
            }
        });
    }


    //Navigation Slider End
    /* script for fixed footer End*/

    // Add a new repeating section
    var attrs = ['name', 'value', 'type', 'data-bv-field'];
    function resetAttributeNames(section, sectionID) {
        if (sectionID == 'question-section') {
            var questionSection = section.parent();
            var questionPart = questionSection.find('.questionPart');
            var section_count = questionPart.length;
        } else {
            var section_count = document.querySelectorAll(sectionID).length;
        }

        --section_count;
        section.attr('name', section_count);
        section.addClass('extraSection');

        var tags = section.find('input, textarea');
        tags.each(function () {
            var $this = $(this);
            $.each(attrs, function (i, attr) {
                var attr_val = $this.attr(attr);
                if ($this.attr('type') != 'button' && attr_val != undefined) {
                    if (attr == 'name') {
                        if (attr_val == 'data[ProjectProvider][0][medical_license_expiry_date]') {
                            $this.attr('id', 'medical_license_expiry_date' + section_count);
                        } else if (attr_val == 'data[ProjectProvider][0][dob]') {
                            $this.attr('id', 'dob' + section_count);
                        } else if (attr_val == 'data[ProjectProvider][0][wcb_authorization_expiry_date]') {
                            $this.attr('id', 'wcb_authorization_expiry_date' + section_count);
                        }

                        var name_array = attr_val.split(']');
                        var new_name = name_array[0] + "][" + section_count + "]" + name_array[2] + "]";
                        $this.attr(attr, new_name);

                    } else if (attr == 'data-bv-field') {
                        var name_array = attr_val.split(']');
                        var new_name = name_array[0] + "][" + section_count + "]" + name_array[2] + "]";
                        $this.attr(attr, new_name);

                    } else if (attr == 'value') {
                        $this.attr(attr, '');
                    }
                }
            })
        });

        if (sectionID == '#portal-section') {

            var quesection = section.find('#question-section');
        }

        if (sectionID == 'question-section') {
            var questionSection = section.parent();
            var questionPart = questionSection.find('.questionPart');
            var nextCount = questionPart.length - 1;
            section.attr('id', 'questionPart' + nextCount);
            section.remove('#addNewQuestion');
        }
        return section_count;
    }

    $('#addNewProvider').click(function (e) {
        e.preventDefault();
        var lastRepeatingGroup = $('#provider-section').last();
        var cloned = lastRepeatingGroup.clone(false);
        cloned.find('#removeProvider').removeClass('hidden');
        cloned.insertAfter(lastRepeatingGroup);
        var section_count = resetAttributeNames(cloned, '#provider-section');

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });

        // Delete a repeating section
        $('.deleteProvider').click(function (e) {
            e.preventDefault();
            var current_fight = $(this).parent('div');
            var other_fights = current_fight.siblings('#provider-section');
            if (other_fights.length === 0) {
                alert("You should atleast have one Provider");
                return;
            }
            current_fight.slideUp('slow', function () {
                current_fight.remove();

                // reset fight indexes
                other_fights.each(function () {
                    resetAttributeNames($(this), '#provider-section');
                })
            })
        });

    });

    // Delete a repeating section
    $('.deleteProvider').click(function (e) {
        e.preventDefault();
        var current_fight = $(this).parent('div');
        var other_fights = current_fight.siblings('#provider-section');
        if (other_fights.length === 0) {
            alert("You should atleast have one Provider");
            return;
        }
        current_fight.slideUp('slow', function () {
            current_fight.remove();

            // reset fight indexes
            other_fights.each(function () {
                resetAttributeNames($(this), '#provider-section');
            })
        })
    });

    $('#addNewLocation').click(function (e) {
        e.preventDefault();
        var lastRepeatingGroup = $('#location-section').last();
        var cloned = lastRepeatingGroup.clone(false);
        cloned.find('#removeLocation').removeClass('hidden');
        cloned.insertAfter(lastRepeatingGroup);
        var section_count = resetAttributeNames(cloned, '#location-section');

        // Delete a repeating section
        $('.deleteLocation').click(function (e) {
            e.preventDefault();
            var current_fight = $(this).parent('div');
            var other_fights = current_fight.siblings('#location-section');
            if (other_fights.length === 0) {
                alert("You should atleast have one Location");
                return;
            }
            current_fight.slideUp('slow', function () {
                current_fight.remove();

                // reset fight indexes
                other_fights.each(function () {
                    resetAttributeNames($(this), '#location-section');
                })
            })
        });
    });

    // Delete a repeating section
    $('.deleteLocation').click(function (e) {
        e.preventDefault();
        var current_fight = $(this).parent('div');
        var other_fights = current_fight.siblings('#location-section');
        if (other_fights.length === 0) {
            alert("You should atleast have one Location");
            return;
        }
        current_fight.slideUp('slow', function () {
            current_fight.remove();

            // reset fight indexes
            other_fights.each(function () {
                resetAttributeNames($(this), '#location-section');
            })
        })
    });

    $('#addNewPortal').click(function (e) {
        e.preventDefault();
        var lastRepeatingGroup = $('#portal-section').last();
        var cloned = lastRepeatingGroup.clone(false);
        cloned.find('#removePortal').removeClass('hidden');
        cloned.insertAfter(lastRepeatingGroup);
        var section_count = resetAttributeNames(cloned, '#portal-section');

        // Delete a repeating section
        $('.deletePortal').click(function (e) {
            e.preventDefault();
            var current_fight = $(this).parent('div');
            var other_fights = current_fight.siblings('#portal-section');
            if (other_fights.length === 0) {
                alert("You should atleast have one Insurance Portal");
                return;
            }
            current_fight.slideUp('slow', function () {
                current_fight.remove();

                // reset fight indexes
                other_fights.each(function () {
                    resetAttributeNames($(this), '#portal-section');
                })
            })
        });
    });

    // Delete a repeating section
    $('.deletePortal').click(function (e) {
        e.preventDefault();
        var current_fight = $(this).parent('div');
        var other_fights = current_fight.siblings('#portal-section');
        if (other_fights.length === 0) {
            alert("You should atleast have one Insurance Portal");
            return;
        }
        current_fight.slideUp('slow', function () {
            current_fight.remove();

            // reset fight indexes
            other_fights.each(function () {
                resetAttributeNames($(this), '#portal-section');
            })
        })
    });
});



function addQuestion(ele) {
    var masterDiv = ele.parent().parent().parent().parent();
    var lastRepeatingGroup = masterDiv.find('.questionPart:last');
    var cloned = lastRepeatingGroup.clone(false);
    cloned.find('#removeQuestion').removeClass('hidden');
    cloned.find('#addNewQuestion').addClass('hidden');
    cloned.insertAfter(lastRepeatingGroup);
    var section_count = resetAttributeNames(cloned, 'question-section');
}


// Delete a repeating section
function removeQuestion(ele) {
    var current_fight = ele.parent().parent().parent();
    current_fight.slideUp('slow', function () {
        current_fight.remove();
    });
}

/* script for fixed footer start*/
$(window).scroll(function (event) {
//    var offset = $(".footer").offset().top;
    // height of the document (total height)
    var scroll = $(window).scrollTop();
    var scrollB = $(document).height() - ($(window).scrollTop() + $(window).height());
    fixbottombtn();
});

//script to keep 	button fix
function fixbottombtn() {
    var d = $(document).height();
    // height of the window (visible page)
    var w = $(window).height();
    // scroll level
    var s = $(this).scrollTop();

    // bottom bound - or the height of your 'big footer'
    var bottomBound = 124;
    // are we beneath the bottom bound?
    if (d - (w + s) < bottomBound) {
        // if yes, start scrolling our own way, which is the
        // bottom bound minus where we are in the page
        $('#fixed').css({
            bottom: bottomBound - (d - (w + s) + 23)
        });
    } else {
        // if we're beneath the bottom bound, then anchor ourselves
        // to the bottom of the page in traditional footer style
        $('#fixed').css({
            bottom: 0
        });

    }
}

var moduleD = angular.module('advancefilter', []);
moduleD.controller('MainCtrl', function ($scope, $compile, $window) {
    var columns = {};
    if ($scope.udtTable.config.advancefilter.active == true) {
        columns = $scope.udtTable.config.advancefilter.fields;
    }
    var text = "";
    var filteri = 0;
    if (columns !== 'undefined') {
        var coloptions = '<option value="" >Select Column</option>';
        $.each(columns, function (key, value) {
            coloptions += '<option value="' + key + '" >' + value + '</option>';
        });
        var operatoroptions = '';
        operatoroptions += '<option value="greaterthan" >Greater than</option>';
        operatoroptions += '<option value="lessthan" >Less than</option>';
        operatoroptions += '<option value="equalto" >Equal to</option>';
        operatoroptions += '<option value="notequalto" >Not equal to</option>';
        operatoroptions += '<option value="greaterthanequalto" >Greater than or equal to</option>';
        operatoroptions += '<option value="lessthanequalto" >Less than or equal to</option>';
        operatoroptions += '<option value="beetween" >Beetween</option>';
        operatoroptions += '<option value="contain" >Contains</option>';
        operatoroptions += '<option value="Doesnotcontain" >Does not contain</option>';
        operatoroptions += '<option value="startswith" >Starts with</option>';
        operatoroptions += '<option value="endswith" >Ends with</option>';
    }
    $scope.advancefilter = function () {
        if (filteri == 0) {
            text += '<div class="row filterrow">';
            text += '   <div class="col-md-12 col-ms-12 col-lg-12 col-xs-12">';
            text += '       <input type="hidden" name="data[' + filteri + '][type]" value="AND" />';
            text += '       <span class="select-style">';
            text += '          <select class="form-control select2" required name="data[' + filteri + '][columns]" >' + coloptions + '</select>';
            text += '       </span>';
            text += '       <span class="select-style">';
            text += '           <select class="form-control select2" required name="data[' + filteri + '][operator]" >' + operatoroptions + '</select>';
            text += '       </span>';
            text += '       <span class="select-style">';
            text += '           <input class="form-control" type="text" required name="data[' + filteri + '][value]" />';
            text += '       </span>';
            text += '       <span ng-click="addadvancefilter(\'and\')" class="icon-and">&amp; </span>';
            text += '       <span ng-click="addadvancefilter(\'or\')" class="icon-or">OR</span>';
            //text += '       <span class="icon icomoon-trash removefilter"> </span>';
            text += '   </div>';
            text += '</div>';
            $("#advancefiltermodal .modal-body").empty();
            var appendtext = $compile(angular.element(text))($scope);
            angular.element(appendtext).appendTo("#advancefiltermodal .modal-body");
            //angular.element($compile(angular.element(text))($scope)).appendTo("#advancefiltermodal .modal-body");
            filteri++;
        }
        $(".select2").select2();
        $("#advancefiltermodal").modal('show');
    }
    $scope.addadvancefilter = function (addtype) {
        if (columns !== 'undefined') {
            text = '';
            text += '<div class="row filterrow">';
            text += '   <div class="col-md-12 col-ms-12 col-lg-12 col-xs-12">';
            text += '       <input type="hidden" name="data[' + filteri + '][type]" value="' + addtype.toUpperCase() + '" />';
            text += '       <div class="col-md-12 col-ms-12 col-lg-12 col-xs-12">' + addtype.toUpperCase() + '</div>';
            text += '       <span class="select-style">';
            text += '          <select class="form-control select2" name="data[' + filteri + '][columns]" >' + coloptions + '</select>';
            text += '       </span>';
            text += '       <span class="select-style">';
            text += '           <select class="form-control select2" name="data[' + filteri + '][operator]" >' + operatoroptions + '</select>';
            text += '       </span>';
            text += '       <span class="select-style">';
            text += '           <input class="form-control" type="text" name="data[' + filteri + '][value]" />';
            text += '       </span>';
            text += '       <span ng-click="addadvancefilter(\'add\')" class="icon-and">&amp; </span>';
            text += '       <span ng-click="addadvancefilter(\'or\')" class="icon-or">OR</span>';
            text += '       <span class="icon icomoon-trash removefilter"> </span>';
            text += '   </div>';
            text += '</div>';
            var appendtext = $compile(angular.element(text))($scope);
            angular.element(appendtext).appendTo("#advancefiltermodal .modal-body");
            filteri++;
            $(".select2").select2();
        }
    }
});
$(document).on('click', '.removefilter', function () {
    $(this).closest('.filterrow').remove();
});

$(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
});


function isEditorEmpty(editor_id, msg) {

    var editorContent = tinyMCE.get(editor_id).getContent();
    if (editorContent == '')
    {
        $("#" + editor_id).parent().after('<div class="custom-tooltip"><div class="top-arrow"></div><div class="error-message">' + msg + '</div></div>');
        return true;
    }

    return false;
}
