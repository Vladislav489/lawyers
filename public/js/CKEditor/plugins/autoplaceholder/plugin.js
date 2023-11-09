// https://stackoverflow.com/questions/1125292/how-to-move-cursor-to-end-of-contenteditable-entity/3866442#3866442
(function( cursorManager ) {

    //From: http://www.w3.org/TR/html-markup/syntax.html#syntax-elements
    var voidNodeTags = ['AREA', 'BASE', 'BR', 'COL', 'EMBED', 'HR', 'IMG', 'INPUT', 'KEYGEN', 'LINK', 'MENUITEM', 'META', 'PARAM', 'SOURCE', 'TRACK', 'WBR', 'BASEFONT', 'BGSOUND', 'FRAME', 'ISINDEX'];

    //From: https://stackoverflow.com/questions/237104/array-containsobj-in-javascript

    if (!Array.prototype.hasOwnProperty('contains')) {
        Array.prototype.contains = function(obj) {
            var i = this.length;
            while (i--) {
                if (this[i] === obj) {
                    return true;
                }
            }
            return false;
        }
    }

    //Basic idea from: https://stackoverflow.com/questions/19790442/test-if-an-element-can-contain-text
    function canContainText(node) {
        if(node.nodeType == 1) { //is an element node
            return !voidNodeTags.contains(node.nodeName);
        } else { //is not an element node
            return false;
        }
    };

    function getLastChildElement(el){
        var lc = el.lastChild;
        while(lc && lc.nodeType != 1) {
            if(lc.previousSibling)
                lc = lc.previousSibling;
            else
                break;
        }
        return lc;
    }

    //Based on Nico Burns's answer
    cursorManager.setEndOfContenteditable = function(contentEditableElement)
    {

        while(getLastChildElement(contentEditableElement) &&
              canContainText(getLastChildElement(contentEditableElement))) {
            contentEditableElement = getLastChildElement(contentEditableElement);
        }

        var range,selection;
        if(document.createRange)//Firefox, Chrome, Opera, Safari, IE 9+
        {
            range = document.createRange();//Create a range (a range is a like the selection but invisible)
            range.selectNodeContents(contentEditableElement);//Select the entire contents of the element with the range
            range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
            selection = window.getSelection();//get the selection object (allows you to change selection)
            selection.removeAllRanges();//remove any selections already made
            selection.addRange(range);//make the range you have just created the visible selection
        }
        else if(document.selection)//IE 8 and lower
        {
            range = document.body.createTextRange();//Create a range (a range is a like the selection but invisible)
            range.moveToElementText(contentEditableElement);//Select the entire contents of the element with the range
            range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
            range.select();//Select the range (make it the visible selection
        }
    }

}( window.cursorManager = window.cursorManager || {}));

(function () {

    CKEDITOR.dtd.$editable.span = 1;

    var tokenSuggestionClass = 'token-suggestions';

    /**
     * Given an input string return all valid tokens in tokenList
     *
     * @param {String} inputValue - what user is typing
     * @param {Object} tokenList - an object with available tokens as key
     * @return {Object} - the object representing the valid token list by key
     *
     */
    function getValidTokens(inputValue, tokenList) {

        // explode input value in different fragments by the dot separator
        var inputSplitted = inputValue.split('.');

        // reduce all available tokens accumulating matching keys
        return Object.keys(tokenList).reduce(function (validTokens, tokenKey) {

            var tokenValue = tokenList[tokenKey];
            var tokenSplitted = tokenKey.split('.');
            var valid = true;
            var lastTokenFragment = '';
            var composedToken = [];

            // for each input fragment check last fragment compatibility
            // if is valid quit the function
            inputSplitted.forEach(function (value, i) {
                lastTokenFragment = tokenSplitted[i];
                composedToken.push(lastTokenFragment);

                // input is an empty string and the loop is at the first index
                if (inputValue === '' && i === 0) return;

                // token fragment doesn't exist return: token is not valid
                if (!tokenSplitted[i]) {
                    valid = false;
                    return;
                }

                // reduce token fragment to input fragment length
                comparableValue = tokenSplitted[i].substr(0, inputSplitted[i].length);
                if (comparableValue.toLowerCase() === inputSplitted[i].toLowerCase()) {
                    return;
                }

                // if precedent checks have not break the function set valid at false
                valid = false;

            });

            // if valid add to accumulator
            if (valid) {
                validTokens[lastTokenFragment] = {
                    tokenKey: tokenKey,
                    data: tokenValue,
                    composedToken: composedToken,
                    lastTokenFragment: lastTokenFragment
                };
            }

            return validTokens;

        }, {});

    }

    /**
     * Change element attributes and fire event when token is match
     * @param {Object} $element The element to change
     * @param {Object} token    Object with token information
     * @param {Object} editor   The editor Object to which fire the event
     */
    function setMatchedToken($element, token, editor) {
        $element.classList.add('completed');
        cursorManager.setEndOfContenteditable($element);

        editor.fire('autoplaceholderTokenMatched', {
            $element: $element,
            tokenData: token.data
        });
    }

    /**
     * Remove attribute from element because is no more matchedwith tokens
     * @param {Object} $element The element to change
     */
    function unsetMatchedToken($element, editor) {
        $element.classList.remove('completed');

        // TODO should be moved outside the plugin
        $element.removeAttribute('rel');

        editor.fire('autoplaceholderTokenUnset', {
            $element: $element
        });
    }

    /**
     * Attach to an element a list of selectable suggestions
     *
     * @param {Object} $element - the element to attach the list
     * @param {Object} suggestions - an object containing all suggestions by key
     * @return {Void}
     */
    function showSuggestions($element, suggestions, editor) {

        // retrieve all the suggestion keys
        var keys = Object.keys(suggestions);

        // if there are no suggestion stop the function
        if (keys.length === 0) return;

        // if there is only one suggestion
        if (keys.length === 1) {

            // check if suggestion final value is equal to the element text stop
            var matched;
            Object.keys(suggestions).forEach(function (suggestionKey) {
                var suggestionValue = suggestions[suggestionKey];
                if (getInputText($element) === suggestionValue.tokenKey) {
                    matched = suggestionValue;
                }
            });

            // if there is one match end the process
            if (matched) {
                setMatchedToken($element, matched, editor);
                return;
            }
        }

        var $suggestionBox = $element.querySelector('.' + tokenSuggestionClass);

        // create box if doesn't exists
        if (!$suggestionBox) {
            $suggestionBox = document.createElement('ul');
            $suggestionBox.classList = tokenSuggestionClass;
            $suggestionBox.style.top = $element.offsetHeight;
            $suggestionBox.setAttribute('contenteditable', false);
            $element.appendChild($suggestionBox);
        }
        // else if it exists empty the box content
        else {
            $suggestionBox.innerHTML = '';
        }

        // add suggestions to list
        Object.keys(suggestions).forEach(function (suggestionKey) {
            var s = suggestions[suggestionKey];
            var $suggestion = document.createElement('li');
            $suggestion.classList = 'suggestion';
            $suggestion.textContent = s.lastTokenFragment;
            $suggestion.onclick = function () {
                var suggestedText = s.composedToken.join('.');
                // if suggestion is not equal to the final token add a dot
                if (suggestedText !== s.tokenKey) {
                    suggestedText += ".";
                    unsetMatchedToken($element, editor);
                } else {
                    setMatchedToken($element, s, editor);
                }
                if ($suggestionBox) {
                    $suggestionBox.remove();
                }

                // set suggested text as element text and move cursor to the end of it
                $element.textContent = suggestedText;

                // if suggestion in not equal to final token trigger a key up to show next suggestions
                if (suggestedText !== s.tokenKey) {
                    $element.dispatchEvent(new Event("keyup"));
                    cursorManager.setEndOfContenteditable($element);
                }
                else {
                    // set cursor at the of the editor content
                    var editorContenteditableWrapper = editor.ui.contentsElement.$.getElementsByClassName('cke_editable');
                    cursorManager.setEndOfContenteditable(editorContenteditableWrapper[0]);
                }

                // emit a change event
                editor.fire('change');
            }
            $suggestionBox.appendChild($suggestion);
        });

    }

    /**
     * Return an input text without altering the original selectElementContents
     * @param  {Object} $element The input element from which retrieve the textarea
     * @return {String}          The input text
     */
    function getInputText($element) {
        var $input = $element.cloneNode(true);
        var suggestion = $input.querySelector('.' + tokenSuggestionClass)
        if (suggestion) {
            suggestion.remove();
        }
        return $input.textContent;
    }

    /**
     * Check if the input (a contenteditable element) has valid suggested tokens
     * @param  {Object} $input    The input element from which take the textarea
     * @param  {Object} tokenList A list of valid token
     * @param  {Object} editor    The editor object
     * @return {Void}
     */
    function checkForValidTokens($input, tokenList, editor) {
        var inputValue = getInputText($input);
        var tokens = getValidTokens(inputValue, tokenList);
        if (inputValue === '') $input.innerHTML = '&nbsp;';
        showSuggestions($input, tokens, editor);
    }

    function selectElementContents(el) {
        var range = document.createRange();
        range.selectNodeContents(el);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    function onKeyDown(e) {
        var $target = e.target;
        var $suggestionBox = $target.querySelector('.' + tokenSuggestionClass);
        var code = e.keyCode || e.which;
        switch (code) {
            case 13: // enter key
                selectActiveSuggestion($suggestionBox);
                break;
            case 38: // arrow up key
                moveSuggestionBackward($suggestionBox);
                break;
            case 40: // arrow down key
            case 9: // tab key
                if (e.shiftKey) {
                    moveSuggestionBackward($suggestionBox);
                } else {
                    moveSuggestionForward($suggestionBox);
                }
                break;
            default:
                return; // exit this handler for other keys
        }

        e.preventDefault();
        e.stopPropagation();

    }

    /**
     * Move the active suggestion list item to the next element
     * @param  {Object} $suggestionBox The suggestion list element
     * @return {Void}
     */
    function moveSuggestionForward($suggestionBox) {
        var $active = $suggestionBox.querySelector('.active');
        // if there an active suggestion move to the next
        if ($active) {
            $active.classList.remove('active');
            if($active.nextElementSibling){
                $active.nextElementSibling.classList.add('active');
            }
            return;
        }

        $suggestionBox.querySelector('.suggestion:first-child').classList.add('active');
    }

    /**
     * Move the active suggestion list item to the previous selectElementContents
     * @param  {Object} $suggestionBox The suggestion list element
     * @return {Void}
     */
    function moveSuggestionBackward($suggestionBox) {
        var $active = $suggestionBox.querySelector('.active');
        // if there an active suggestion move to the previous
        if ($active) {
            $active.classList.remove('active');
            if($active.previousElementSibling){
                $active.previousElementSibling.classList.add('active');
            }

            return;
        }
        $suggestionBox.querySelector('.suggestion:last-child').classList.add('active');
    }

    /**
     * Select the activate suggestion list item
     * @param  {Object} $suggestionBox The suggestion list selectElementContents
     * @return {Void}
     */
    function selectActiveSuggestion($suggestionBox) {
        var $active = $suggestionBox.querySelector('.active');
        // if there an active suggestion trigger the click event
        if ($active) {
            $active.dispatchEvent(new Event("click"));
        }
    }

    // register plugin
    CKEDITOR.plugins.add('autoplaceholder', {
        requires: 'widget',

        icons: 'autoplaceholder',

        init: function (editor) {

            if (typeof editor.config.autoplaceholder === 'undefined') {
                throw new Error("Autoplaceholder plugin can't work without configurations: provide an object with tokenList property");
            }

            var tokenList = editor.config.autoplaceholder.tokenList;
            var defaultText = editor.config.autoplaceholder.defaultText || "Insert a value";

            // add widget
            editor.widgets.add('autoplaceholder', {
                button: 'Create a placeholder',
                template:
                    '<span class="autoplaceholder">' +
                    '<span class="autoplaceholder-token">' + defaultText + '</span>' +
                    '</span>',
                editables: {
                    content: {
                        selector: '.autoplaceholder-token',
                        allowedContent: 'plain-text'
                    }
                },
                inline: true,
                allowedContent: 'span(!autoplaceholder); span(!autoplaceholder-token)',
                requiredContent: 'span(autoplaceholder)',
                upcast: function (element) {
                    // activate widget on existing placeholder
                    return element.name === 'span' && element.classes && element.classes.includes('autoplaceholder');
                },
                init: function () {
                    var self = this;
                    this.on('ready', function (e) {
                        var token = e.sender.element.$.querySelector('.autoplaceholder-token');
                        token.innerHTML = token.innerText;

                        token.addEventListener('focus', function (e) {
                            // select the widget text
                            selectElementContents(token);

                            // on focus save the current text in data
                            token.data = {before: getInputText(token)};
                        }, false);
                        token.addEventListener('blur', function () {

                            if (token.querySelector('.' + tokenSuggestionClass)) {
                                token.querySelector('.' + tokenSuggestionClass).remove();
                            }
                        }, false);
                        token.addEventListener('keydown', onKeyDown, false);
                        token.addEventListener('keyup', function (e) {
                            // ignore keypress used by suggestion list navigation
                            var code = e.keyCode || e.which;
                            switch (code) {
                                case 13: // enter
                                case 38: // arrow up
                                case 40: // arrow down
                                case 9: // tab
                                    return;
                            }

                            var inputValue = getInputText(token);
                            // if content is changed
                            if (token.data.before !== inputValue) {
                                // save new value
                                token.data = {before: inputValue};
                                unsetMatchedToken(token, editor);
                                // if user insert almost two chars or an empty string check for suggestion and show
                                if (inputValue.length > 1 || inputValue === '') {
                                    checkForValidTokens(token, tokenList, editor);
                                }
                            }
                        }, false);

                    });
                }
            });
        }
    });

})();
