/* =========================================================
 * composer-atts.js v0.2.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer backbone/underscore shortcodes attributes
 * form field and parsing controls
 * ========================================================= */
var vc = {filters: {templates: []}, addTemplateFilter: function(callback) {
        if (_.isFunction(callback))
            this.filters.templates.push(callback);
    }};
(function($) {
    var i18n = window.i18nLocale;
    vc.edit_form_callbacks = [];
    vc.atts = {
        parse: function(param) {
            var value;
            var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');

            if (!_.isUndefined(vc.atts[param.type]) && !_.isUndefined(vc.atts[param.type].parse)) {
                value = vc.atts[param.type].parse.call(this, param);
            } else {
                value = $field.length ? $field.val() : null;
            }
            if ($field.data('js-function') !== undefined && typeof(window[$field.data('js-function')]) !== 'undefined') {
                var fn = window[$field.data('js-function')];
                fn(this.$el, this);
            }
            return value;
        },
        parseFrame: function(param) {
            var value;
            var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
            if (!_.isUndefined(vc.atts[param.type]) && !_.isUndefined(vc.atts[param.type].parse)) {
                value = vc.atts[param.type].parse.call(this, param);
            } else {
                value = $field.length ? $field.val() : null;
            }
            if ($field.data('js-function') !== undefined && typeof(window[$field.data('js-function')]) !== 'undefined') {
                var fn = window[$field.data('js-function')];
                fn(this.$el, this);
            }
            return value;
        },
        init: function(param, $field) {
            if(param && param.type){
                if (!_.isUndefined(vc.atts[ param.type ]) && !_.isUndefined(vc.atts[ param.type ].init)) {
                    vc.atts[ param.type ].init.call(this, param, $field);
                }
            }
        }
    };
    /**
     * Auto Complete PARAMETER
     *
     */
    var VC_AutoComplete = Backbone.View.extend({
        min_length: 2,
        delay: 500,
        auto_focus: true,
        ajax_url: window.ajaxurl,
        source_data: function() {
            return {};
        },        
        initialize: function(params) {
            _.bindAll(this, 'init', 'addAccessory', 'delAccessory', 'getAccessoriesIds');
            params = $.extend({
                min_length: this.min_length,
                delay: this.delay,
                auto_focus: this.auto_focus,
                multiple: false
            }, params);
            this.options = params;
            this.param_name = this.options.param_name;
            this.$el = this.options.$el;
            vc.vc_sds_autocomplete_field_object = this;
            this.$el_wrap = this.$el.parent();
            this.$sortable_wrapper = this.$el_wrap.parent();
            this.$input_param = this.options.$param_input;
            this.selected_items = [];
            this.isMultiple = params.multiple;            
            this.mainInput = this.$el.closest('.vc_autocomplete-field').find('input.wpb_vc_param_value');
            this.selectionList = this.$el.closest('.vc_autocomplete-field').find('div.selected-items');
            
            this.render();
        },
        init: function(){
            var currentElemObj = this;
            this.$el.autocomplete(window.vc_ajaxurl 
                    + '&action=vc_get_autocomplete_suggestion&vc_catalog_type='+this.options.vc_catalog_type, {
                minChars: 2,
                autoFill: true,
                max: 20,
                matchContains: true,
                mustMatch: false,
                scroll: false,
                cacheLength: 0,
                formatItem: function(item) {
                    vc.vc_sds_autocomplete_field_object = currentElemObj;
                    return item[1] + ' - ' + item[0];
                }
            }).result(this.addAccessory);
        },
        addAccessory: function(event, data, formatted){
            if (data == null)
                    return false;
                var productId = data[1];
                var productName = data[0];
                
                var $divAccessories = vc.vc_sds_autocomplete_field_object.selectionList;
                var $inputAccessories = vc.vc_sds_autocomplete_field_object.mainInput;                
                var $nameAccessories = $inputAccessories;
                
                var $divAccessoriesHtml = '', $nameAccessoriesHtml = '', $inputAccessoriesHtml = '';
                
                if(vc.vc_sds_autocomplete_field_object.isMultiple){
                    $divAccessoriesHtml += $divAccessories.html();
                    $nameAccessoriesHtml += $nameAccessories.attr('data-names');
                    $inputAccessoriesHtml += $inputAccessories.val();
                }
                
                $divAccessoriesHtml += '<div class="form-control-static"><button type="button" class="delAccessory btn btn-default" name="' + productId + '"><i class="icon-remove text-danger"></i></button>&nbsp;' + productName + '</div>';                
                $nameAccessoriesHtml += productName;
                $inputAccessoriesHtml += productId;
                
                if(vc.vc_sds_autocomplete_field_object.isMultiple){
                    $nameAccessoriesHtml += '¤';
                    $inputAccessoriesHtml += '-';
                }
                
                $divAccessories.html($divAccessoriesHtml);
                $nameAccessories.attr('data-names',$nameAccessoriesHtml);
                $inputAccessories.val($inputAccessoriesHtml);
                
                /* delete product from select + add product line to the div, input_name, input_ids elements */
                
                vc.vc_sds_autocomplete_field_object.$el.val('');
                vc.vc_sds_autocomplete_field_object.$el.setOptions({
                    extraParams: {
                        excludeIds: vc.vc_sds_autocomplete_field_object.getAccessoriesIds($inputAccessories)
                    }
                });
        },
        delAccessory: function(delElem){
            var div = delElem.closest('.vc_autocomplete-field').find('div.selected-items');
            var input = delElem.closest('.vc_autocomplete-field').find('input.wpb_vc_param_value');
            var name = input;
            var id = delElem.attr('name');
            vc.vc_sds_autocomplete_field_object = this;
            
            
            // Cut hidden fields in array
            var inputCut = input.val().split('-');
            var nameCut = name.attr('data-names').split('¤');

            if (inputCut.length != nameCut.length)
                return jAlert('Bad size');

            // Reset all hidden fields
            input.val('');
            name.val('');
            div.html('');
            var inputVal = '', nameVal = '', divHtml = '';
            for (var i in inputCut)
            {
                // If empty, error, next
                if (!inputCut[i] || !nameCut[i])
                    continue;

                if (typeof inputCut[i] == 'function') // to resolve jPaq issues
                    continue;

                // Add to hidden fields no selected products OR add to select field selected product
                if (inputCut[i] != id)
                {
                    inputVal += inputCut[i] + '-';
                    nameVal += nameCut[i] + '¤';
                    divHtml += '<div class="form-control-static"><button type="button" class="delAccessory btn btn-default" name="' + inputCut[i] + '"><i class="icon-remove text-danger"></i></button>&nbsp;' + nameCut[i] + '</div>';
                }
                
            }

            input.val(inputVal);
            name.attr('data-names',nameVal);
            div.html(divHtml);
            this.$el.setOptions({
                extraParams: {
                    excludeIds: this.getAccessoriesIds(input)
                }
            });
        },
        getAccessoriesIds: function(elem){
            
            return elem.val().replace(/\-/g, ',');
        },
        render: function() {
            this.init();
            var cobj = this;
            this.$el.setOptions({
                extraParams: {
                    excludeIds: this.getAccessoriesIds(this.mainInput)
                }
            });
            
            this.selectionList.on('click', '.delAccessory', function() {
                cobj.delAccessory($(this));
            });

            return this;
        }
        
    });
    // Default atts
    _.extend(vc.atts, {
        textarea_html: {
            parse: function(param) {

                var $field = this.content().find('.textarea_html.' + param.param_name + ''),
                        mce_id = $field.attr('id');

//                return this.window().tinyMCE && this.window().tinyMCE.activeEditor
//                       ? this.window().tinyMCE.activeEditor.save()
//                       : $field.val();

                return $field.val();
            },
            render: function(param, value) {
                return _.isUndefined(value) ? value : vc_wpautop(value);
            }
        },
        textarea_safe: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                        new_value = $field.val();
                return new_value.match(/"/) ? '#E-8_' + base64_encode(rawurlencode(new_value)) : new_value;
            },
            render: function(param, value) {
                return value && value.match(/^#E\-8_/) ? $("<div/>").text(rawurldecode(base64_decode(value.replace(/^#E\-8_/, '')))).html() : value;
            }
        },
        checkbox: {
            parse: function(param) {
                var arr = [],
                        new_value = '';
                $('input[name=' + param.param_name + ']', this.content()).each(function(index) {
                    var self = $(this);
                    if (self.is(':checked')) {
                        arr.push(self.attr("value"));
                    }
                });
                if (arr.length > 0) {
                    new_value = arr.join(',');
                }
                return new_value;
            }
        },
        posttypes: {
            parse: function(param) {
                var posstypes_arr = [],
                        new_value = '';
                $('input[name=' + param.param_name + ']', this.content()).each(function(index) {
                    var self = $(this);
                    if (self.is(':checked')) {
                        posstypes_arr.push(self.attr("value"));
                    }
                });
                if (posstypes_arr.length > 0) {
                    new_value = posstypes_arr.join(',');
                }
                return new_value;
            }
        },
        taxonomies: {
            parse: function(param) {
                var posstypes_arr = [],
                        new_value = '';
                $('input[name=' + param.param_name + ']', this.content()).each(function(index) {
                    var self = $(this);
                    if (self.is(':checked')) {
                        posstypes_arr.push(self.attr("value"));
                    }
                });
                if (posstypes_arr.length > 0) {
                    new_value = posstypes_arr.join(',');
                }
                return new_value;
            }
        },
        exploded_textarea: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
                return $field.val().replace(/\n/g, ",");
            }
        },
        textarea_raw_html: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                        new_value = $field.val();
                return base64_encode(rawurlencode(new_value));
            },
            render: function(param, value) {
                return $("<div/>").text(rawurldecode(base64_decode(value))).html();
            }
        },
        dropdown: {
            render: function(param, value) {
                var all_classes = _.isObject(param.value) ? _.values(param.value).join(' ') : '';
                //  this.$el.find('> .wpb_element_wrapper').removeClass(all_classes).addClass(value); // remove all possible class names and add only selected one
                return value;
            }
        },
        attach_images: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                        thumbnails_html = '';
                // TODO: Check image search with Wordpress
                $field.parent().find('li.added').each(function() {
                    thumbnails_html += '<li><img src="' + $(this).find('img').attr('src') + '" alt=""></li>';
                });
                $('[data-model-id=' + this.model.id + ']').data('field-' + param.param_name + '-attach-images', thumbnails_html);
                return $field.length ? $field.val() : null;
            },
            render: function(param, value) {
                var $thumbnails = this.$el.find('.attachment-thumbnails[data-name=' + param.param_name + ']'),
                        thumbnails_html = this.$el.data('field-' + param.param_name + '-attach-images');
                if (_.isUndefined(thumbnails_html) && !_.isEmpty(value)) {
                    $.ajax({
                        type: 'POST',
                        url: window.vc_ajaxurl,
                        data: {
                            action: 'wpb_gallery_html',
                            content: value
                        },
                        dataType: 'html',
                        context: this
                    }).done(function(html) {
                        vc.atts.attach_images.updateImages($thumbnails, html);
                    });
                } else if (!_.isUndefined(thumbnails_html)) {
                    this.$el.removeData('field-' + param.param_name + '-attach-images');
                    vc.atts.attach_images.updateImages($thumbnails, thumbnails_html);
                }
                return value;
            },
            updateImages: function($thumbnails, thumbnails_html) {
                $thumbnails.html(thumbnails_html);
                if (thumbnails_html.length) {
                    $thumbnails.removeClass('image-exists').next().addClass('image-exists');
                } else {
                    $thumbnails.addClass('image-exists').next().removeClass('image-exists');
                }
            }
        },
        href: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                        val = '';
                if ($field.length && $field.val() != 'http://')
                    val = $field.val();
                return val;
            }
        },
        attach_image: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']'),
                        image_src = '';
                if ($field.parent().find('li.added').length) {
                    image_src = $field.parent().find('li.added img').attr('src');
                }
                $('[data-model-id=' + this.model.id + ']').data('field-' + param.param_name + '-attach-image', image_src);
                return $field.length ? $field.val() : null;
            },
            render: function(param, value) {
                var image_src = $('[data-model-id=' + this.model.id + ']').data('field-' + param.param_name + '-attach-image');
                var $thumbnail = this.$el.find('.attachment-thumbnail[data-name=' + param.param_name + ']');
                if (_.isUndefined(image_src) && !_.isEmpty(value)) {

                    $.ajax({
                        type: 'POST',
                        url: window.vc_ajaxurl,
                        data: {
                            action: 'wpb_single_image_src',
                            content: value
                        },
                        dataType: 'html',
                        context: this
                    }).done(function(src) {
                        vc.atts['attach_image'].updateImage($thumbnail, src);
                    });
                } else if (!_.isUndefined(image_src)) {
                    $('[data-model-id=' + this.model.id + ']').removeData('field-' + param.param_name + '-attach-image');
                    vc.atts['attach_image'].updateImage($thumbnail, image_src);
                }

                return value;
            },
            updateImage: function($thumbnail, image_src) {
                if (_.isEmpty(image_src)) {
                    $thumbnail.attr('src', '').hide();
                    $thumbnail.next().removeClass('image-exists').next().removeClass('image-exists');
                } else {
                    $thumbnail.attr('src', image_src).show();
                    $thumbnail.next().addClass('image-exists').next().addClass('image-exists');
                }
            }
        },
        google_fonts: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
                var $block = $field.parent();
                var options = {},
                        string_pieces = [],
                        string = '';
                options.font_family = $block.find('.vc_google_fonts_form_field-font_family-select > option:selected').val();
                options.font_style = $block.find('.vc_google_fonts_form_field-font_style-select > option:selected').val();
                string_pieces = _.map(options, function(value, key) {
                    if (_.isString(value) && value.length > 0) {
                        return key + ':' + encodeURIComponent(value);
                    }
                });
                string = $.grep(string_pieces, function(value) {
                    return _.isString(value) && value.length > 0;
                }).join('|');
                return string;
            }
        },
        font_container: {
            parse: function(param) {
                var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
                var $block = $field.parent();
                var options = {},
                        string_pieces = [],
                        string = '';
                options.tag = $block.find('.vc_font_container_form_field-tag-select > option:selected').val();
                options.font_size = $block.find('.vc_font_container_form_field-font_size-input').val();
                options.text_align = $block.find('.vc_font_container_form_field-text_align-select > option:selected').val();
                options.font_family = $block.find('.vc_font_container_form_field-font_family-select > option:selected').val();
                options.color = $block.find('.vc_font_container_form_field-color-input').val();
                options.line_height = $block.find('.vc_font_container_form_field-line_height-input').val();
                options.font_style_italic = $block.find('.vc_font_container_form_field-font_style-checkbox.italic').is(':checked') ? "1" : "";
                options.font_style_bold = $block.find('.vc_font_container_form_field-font_style-checkbox.bold').is(':checked') ? "1" : "";
                string_pieces = _.map(options, function(value, key) {
                    if (_.isString(value) && value.length > 0) {
                        return key + ':' + encodeURIComponent(value);
                    }
                });
                string = $.grep(string_pieces, function(value) {
                    return _.isString(value) && value.length > 0;
                }).join('|');
                return string;
            }
        },
        autocomplete: {
            init: function(param, $field) {
                var $el_type_autocomplete = $field;
                if ($el_type_autocomplete.length) {
                    $el_type_autocomplete.each(function() {
                        var $param = $('.wpb_vc_param_value', this);
                        var param_name = $param.attr('name');
                        var $el = $('.vc_auto_complete_param', this);
                        var options = {};
                        options = $.extend(
                                {
                                    $param_input: $param,
                                    $el: $el,
                                    param_name: param_name
                                },
                        $param.data('settings')
                                );
                        new VC_AutoComplete( options );

                    });

                }
            }
        }
    });
    vc.getMapped = function(tag) {
        return vc.map[tag] || {};
    };
})(window.jQuery);