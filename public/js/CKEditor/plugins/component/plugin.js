(function ($) {
    CKEDITOR.plugins.add('component', {
        requires: 'widget',
        init: function(editor) {
            editor.dataOption ={};
            editor.list = [];
            CKEDITOR.dialog.add( 'componentDialog', this.path + 'dialogs/component.js' );
            var $this = this;
            var list = document.querySelector("#"+editor.connnectElementComponent).querySelectorAll("li[id]");
            for(var index = 0 ; index < list.length; index++){
                var id = list[index].getAttribute('id');
                editor.list.push('new_component-'+id);
                editor.widgets.add(id, {
                    requiredContent: 'component(new_component-'+id+')',
                    pathName: id,
                    dialog: 'componentDialog',
                    data: function() {},
                    init: function() {},
                    edit: function() {
                    },
                    upcast: function (el) {
                        var rez = (el.name == 'component' &&  editor.list.indexOf(el.attributes.class)!=-1)
                        if(rez){
                            this.idComponent = el.attributes.class.replace('new_component-','');
                        }
                        return rez;
                    }
                });


                editor.addCommand( id, new CKEDITOR.dialogCommand( 'componentDialog' ) );
                editor.addFeature(editor.widgets.registered[id]);
            }
            editor.on('paste', function (evt) {
                var componentData = evt.data.dataTransfer.getData('component');
                if (!componentData) {return;}
                evt.data.dataValue = "<component class='new_component-"+componentData['item']['id']+"'>"+componentData['script']+"\n<component_template data-id=''>"+componentData['template']+"<component_template>\n</component>";
                console.log(componentData);
                editor.dataOption[componentData['item']['id']] = componentData;
            });

        }
    });

})(jQuery);
