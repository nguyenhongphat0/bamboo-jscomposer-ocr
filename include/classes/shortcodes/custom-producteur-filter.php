<?php
class WPBakeryShortCode_custom_producteur_filter extends WPBakeryShortCode {
    protected function content($atts, $content = null) { ?>
        <!-- CUSTOM PRODUCTEUR FILTER -->
        <!-- modify this in 'modules/jscomposer/include/classes/shortcodes/custom-producteur-filter.php' -->
        <style>
            .producteur-filter .input-box {
                display: inline-block;
                max-width: 100%;
                margin: 20px 0px;
                position: relative;
            }
            .producteur-filter .input-box .ttsearch_button {
                position: absolute;
                right: 20px;
                color: black;
                cursor: pointer;
            }
            .producteur-filter .input-box input {
                padding: 5px;
                width: 300px;
                max-width: 100%;
                text-align: center;
                border: none;
                border-radius: 20px;
            }
            .bg-blue {
                padding: 20px;
                background: #222456;
                color: white;
                text-align: center;
            }
            .hover-orange {
                font-size: 20px;
                cursor: pointer;
                padding: 10px;
                font-weight: bold;
                display: inline-block;
            }
            .hover-orange.active, .hover-orange:hover {
                color: #DDA00C !important;
            }
        </style>
        <div class="producteur-filter bg-blue">
            <div class="input-box">
                <input class="input-keyword" type="text" value="" placeholder="Tapez votre recherche" onkeyup="commitKeyword(this)">
                <span class="ttsearch_button" onclick="filter()">
                    <i class="material-icons search"></i>
                </span>
            </div>
            <div class="alphabet-box">
            </div>
            <div class="no-result-found hover-orange" style="display: none">
                No result found
            </div>
        </div>
        <script>
            function commitKeyword(that) {
                producteurFilter.keyword = $(that).val().toLowerCase();
                filter();
            }
            function commitLetter(x) {
                producteurFilter.letter = x;
                filter();
            }
            function filter() {
                $('.no-result-found').hide()
                $('.hover-orange').removeClass('active');
                $('.hover-orange').filter(function(index) {
                    return $(this).text() == producteurFilter.letter;
                }).addClass('active');
                $('.bamboo.a-producteur').hide();
                let results = $('.bamboo.a-producteur').filter(function(index) {
                    let name = $(this).data('name').toLowerCase();
                    return name.startsWith(producteurFilter.letter) && name.indexOf(producteurFilter.keyword) >= 0;
                });
                results.show();
                if (results.length == 0) {
                    $('.no-result-found').show()
                }
            }
            function initAlphabet() {
                for (var i = 97; i <= 122; i++) {
                    let ch = String.fromCharCode(i);
                    $('.alphabet-box').append('<a class="hover-orange" onclick="commitLetter(\'' + ch + '\')">' + ch + '</a>');
                }
                producteurFilter = {
                    keyword: '',
                    letter: 'a'
                }
            }
            window.onload = function() {
                initAlphabet();
                filter();
            }
        </script>
    <?php }
}