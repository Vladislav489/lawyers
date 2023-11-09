@include('component_build',["component" => 'component.menuComponent.menu',
                             "params_component" => [
                                    'name'=>"menu_admin",
                                    'params'=>[
                                        "sort_by"=>['sort'],
                                     ],
                                    'autostart' => 'false',
                                    'url'=>route__('actionSystemsMenu_backcontroller'),
                                    'data'=> 'null',
                                    'template'=>"menu",
                                    'templateItem'=>"menuItem",
                                    'ssr'=>'true'
                             ]
                           ])