CKEDITOR.dialog.add('glossaryDialog', function(editor) {
    return {

        title: 'Глоссарий',
        minWidth: 400,
        minHeight: 200,

        contents: [

            {
                id: 'tab-id',
                label: 'Добавить новый термин',

                requiredContent: 'glossary[id]',
                elements: [{
                    type: 'text',
                    id: 'glossary',
                    label: 'Поиск термина',
                    validate: CKEDITOR.dialog.validate.notEmpty("Термин не может быть пустым"),

                    setup: function(element) {
                        this.setValue(element.getText());
                    },

                    commit: function(element) {
                        element.setText(this.getValue());
                    },


                }, {
                    type: 'select',
                    id: 'title',
                    label: 'Выберите термин',
                    items: [['-', '-']],

                    setup: function(element) {
                        this.setValue(element.getAttribute("title"));
                        },

                    commit: function(element) {
                      var input = this.getInputElement().$;
                      element.setAttribute("term", input.options[input.selectedIndex].text);
                      element.setAttribute("title", this.getValue());
                    }
                }, ]
            }
        ],


        onShow: function() {
            this.setValueOf( 'tab-id', 'glossary', editor.getSelection().getSelectedText().toString() );

            var glossary = '';
            var data = {
                title: editor.getSelection().getSelectedText().toString()
            };

            $.getJSON("/api/terms/", data, function(json) {

                for (var i = 0; i < json.length; i++) {
                    glossary += "<option value=\"" + json[i].definition + "\">" + json[i].title + "</option>";
                }
                var selectId = '#' + CKEDITOR.dialog.getCurrent().getContentElement('tab-id', 'title').getInputElement().$.id;
                $(selectId).html(glossary);

            });


            var selection = editor.getSelection();
            var element = selection.getStartElement();

            if (element)
                element = element.getAscendant('glossary', true);

            if (!element || element.getName() != 'glossary') {
                element = editor.document.createElement('glossary');
                this.insertMode = true;
            } else
                this.insertMode = false;

            this.element = element;
            if (!this.insertMode)
                this.setupContent(this.element);
        },

        onOk: function() {

            var dialog = this;
            var glossary = this.element;

            this.commitContent(glossary);

            if (this.insertMode)
                editor.insertElement(glossary);
        }
    };
});
