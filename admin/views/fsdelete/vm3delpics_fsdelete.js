jQuery(function(){
	
	var myOptions = Joomla.getOptions('com_vm3delpics');
	
    jQuery( document ).ready(function() {
		//добываем данные
        var time_limit = 25;//временной лимит(сек) на ответ сервера
        var i = 0;
        var go = 1;//признак того, что нужно продолжать считывать информацию о файлах
        var from = 0;//с какого файла по порядку начинаем проверять
        var read_cnt = 10;//число проверяемых файлов

        var request = function(){
            jQuery.getJSON('index.php?option=com_vm3delpics&task=fsdelete.read&format=json'
                , {
                    data: {
                        "from":from,
                        "read_cnt":read_cnt
                    }
                })
                .always(function(r) {
                    var start = new Date().getTime();

                    if (!r.success && r.message){
                        alert(r.message);
                    }
                    if (r.messages){
                        Joomla.renderMessages(r.messages);
                    }
                    if (r.data){
                        var elapsed = new Date().getTime() - start;
                        if(elapsed/1000 - 0.5 < time_limit){
                            read_cnt = r.data.read_cnt+1;
                        }
                        else{
                            if(r.data.read_cnt > 1)	read_cnt = r.data.read_cnt-1;
                            else 
								alert(myOptions.slow_responce);
								/*jQuery.getJSON( 'index.php?option=com_vm3delpics&task=fsdelete.givemetheconstant&format=json'
                                        , { data: {"req_const":"COM_VM3DELPICS_ADMIN_RESPONCE_TOO_SLOW"}
                                    })
                                    .always(function(r) {
                                        if (!r.success && r.message) alert(r.message);
                                        if (r.messages) Joomla.renderMessages(r.messages);
                                        if (r.data) alert(r.data);
                                    });*/

                        }
                        from = r.data.from + r.data.read_cnt;

                        //отображение найденных файлов
                        var tbl = jQuery("#fsscanres")[0];
						var tbody = tbl.tBodies[0];
                        var rws = tbl.rows;
                        var lst = rws[rws.length - 2];
                        var cls = lst.cells.length;
                        var rows_length = rws.length;

						for (var prop in r.data.files) {
							if( r.data.files.hasOwnProperty( prop ) ) {
								i++;
								var ro = tbody.insertRow(-1);
								for (var j = 0; j < cls; j++){
									var ce = ro.insertCell(-1);
									switch(j){
										case 0 :
											ce.innerHTML = rows_length + i - 2;
											break;
										case 1 :
											ce.innerHTML = '<input type="checkbox" id="cb'+i+'" name="cid[]" value="'+ r.data.files[prop]["file_url"] + '" onclick="Joomla.isChecked(this.checked);">';
											break;
										case 2 :
											ce.innerHTML = '<a name="modal" title="Заголовок" href="../'+r.data.files[prop]["file_url"]+'">'+r.data.files[prop]["file_url"]+'</a>';
											break;
									}
								}
							}
						}
                        i = 0;
						
                    }
                    if(r.data.go) request();
					
                });
        }
        request();
		
		//готовим модальное окно для просмотра картинок по ссылкам
		jQuery('body').append("<div id='boxes'><div id='dialog' class='window'>Текст модального окна<div class='top'><a href='#' class='link close />Закрыть</a></div><div class='content'>Текст в модальном окне.</div></div></div><div id='mask></div>");
		
		//обработчики модального окна
		jQuery('a[name=modal]').click(function(e) {
			e.preventDefault();
			var id = jQuery(this).attr('href');
			var maskHeight = jQuery(document).height();
			var maskWidth = jQuery(window).width();
			jQuery('#mask').css({'width':maskWidth,'height':maskHeight});
			jQuery('#mask').fadeIn(1000); 
			jQuery('#mask').fadeTo("slow",0.8); 
			var winH = jQuery(window).height();
			var winW = jQuery(window).width();
			jQuery(id).css('top',  winH/2-jQuery(id).height()/2);
			jQuery(id).css('left', winW/2-jQuery(id).width()/2);
			jQuery(id).fadeIn(2000);
		});
		jQuery('.window .close').click(function (e) { 
			e.preventDefault();
			jQuery('#mask, .window').hide();
		}); 
		jQuery('#mask').click(function () {
			jQuery(this).hide();
			jQuery('.window').hide();
		}); 
		//обработчики модального окна - конец
						
		//jQuery("a").addClass("modal");
    });


		
    function delete_selected(arr){
        jQuery.getJSON( 'index.php?option=com_vm3delpics&task=fsdelete.deleteFSSelected&format=json'
            , {
                data: {
                    "files":arr
                }
            })
            .always(function(r) {
                if (!r.success && r.message){
                    alert(r.message);
                }
                if (r.messages){
                    Joomla.renderMessages(r.messages);
                }
                if (r.data){
                    //удаление сведений о файлах в панели
                    r.data.forEach(function(item, i, arr) {
                        jQuery('td:contains("'+item+'")').parent().remove();
                    });
                    jQuery("thead input:checkbox").removeAttr("checked");
                }
            });
    }

	//удаление выбранных файлов
	jQuery("#toolbar-delete .btn-small").each(function () {
		//var joomla_handler = this.onclick;
		this.onclick = null;
		jQuery(this).click(function(){
			var arr = jQuery('tbody :checkbox:checked').map(function(i, el){
				return jQuery(el).val();
			}).get();
			if (arr.length){
				if(confirm(myOptions.delete_selected)) delete_selected(arr);
				//joomla_handler();
			}
			else alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));
		});
	});

	//удаление всех обнаруженных файлов
	jQuery("#toolbar-deletes .btn-small").each(function () {
		this.onclick = null;
		jQuery(this).click(function(){
			var arr = jQuery('tbody :checkbox').map(function(i, el){
                return jQuery(el).val();
			}).get();
			if(confirm(myOptions.sure_all)) delete_selected(arr);
	    });
    });

	
});
