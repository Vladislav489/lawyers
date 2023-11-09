<script>
    class pageEditor {
        constructor(option) {
            this.HtmlDom
            this.listTag
            this.Connectelement;// = option['Connectelement'];
            this.init();
        }
        init(){
            CKEDITOR.replace('editor1');
           for(var key in  this.Connectelement){

               CKEDITOR.plugins.add('componentSite',{
                   requires: 'widget',
                   init:function(editor){


                       editor.widgets.add('componentSite',{
                           allowedContent: 'span(!h-card); a[href](!u-email,!p-name); span(!p-tel)',
                           requiredContent: 'span(h-card)',
                           pathName: 'componentSite',
                           upcast: function(el) {
                               return el.name == 'span' && el.hasClass('h-card');
                           }
                       });
                       editor.addFeature(editor.widgets.registered.componentSite);



                       editor.on('paste',function(event){
                           console.log("drag")
                           var contact = evt.data.dataTransfer.getData('contact');
                           if (!contact) {
                               return;
                           }
                           //это создается в html
                           evt.data.dataValue =
                               '<span class="h-card">' +
                               '<a href="mailto:' + contact.email + '" class="p-name u-email">' + contact.name + '</a>' +
                               ' ' +
                               '<span class="p-tel">' + contact.tel + '</span>' +
                               '</span>';
                       })
                   },
               })
           }





            CKEDITOR.on('instanceReady', function() {
                CKEDITOR.document.getById('compunentList').on('dragstart', function(evt) {
                    var target = evt.data.getTarget().getAscendant('div', true);
                    CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
                    var dataTransfer = evt.data.dataTransfer;
                    dataTransfer.setData('contact', CONTACTS[target.data('contact')]);
                    dataTransfer.setData('text/html', target.getText());
                    if (dataTransfer.$.setDragImage) {
                        dataTransfer.$.setDragImage(target.findOne('img').$, 0, 0);
                    }
                });
            });
        }
    }






</script>