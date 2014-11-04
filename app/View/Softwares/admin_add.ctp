
<div id="m">
        <ul><?php $t=1; ?>
                <li <?php if($t==1) echo ' style="background:#e7eff4"'; ?>><a href="/admin/Softwares/add">普通软件</a></li> 
                <li <?php if($t==2) echo ' style="background:#e7eff4"'; ?>><a href="/admin/Softwares/addios">IOS软件</a></li>
        </ul>
</div> 
  <div class="k" style="display:block">
    <?php echo $this->Form->create('Software',array('onSubmit'=>'return funcChina();','div'=>false,'name'=>'frm','enctype' => 'multipart/form-data','action'=>'pmsave')); ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                            <td class="call_1">是否为程序更新：</td>
                                            <td class="call_3"><input type="checkbox"  id="isCheck" value="1" name="isCheck"></td>
                                    </tr>

                                    <tr id="div_text" style="display: none;">
                                            <td class="call_1">需要更新的程序：</td>
                                            <td class="call_3">
                                                <?php
													echo $this->Form->input('psoft_id',
														array(
														'type'	=> 'select',
														'label' => false,
														'div' => false,
														'class' => false,
														'empty' => '——请选择版本类别——',
                                                        'onchange' => 'showDiv()',
														'options' => $lists
														)
											   );?>
                                                <span style="color:#c00">*</span>
                                            </td>
                                    </tr>

                                    <tr>
                                            <td class="call_1">程序名称：</td>
                                            <td class="call_3"><input type="text" class="pjad" id="name" name="name"/><span style="color:#c00">*</span></td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">版本号：</td>
                                            <td class="call_3"><input type="text" class="pjad" id="version"  name="version"/><span style="color:#c00">*</span></td>
                                    </tr>

									<tr>
                                            <td class="call_1">版本类别：</td>
                                            <td class="call_3">
                                               <?php
													echo $this->Form->input('soft_id',
														array(
														'type'	=> 'select',
														'label' => false,
														'div' => false,
														'class' => false,
														'empty' => '——请选择版本类别——',
														'options' => $softlist
														)
											   );?>
                                                <span style="color:#c00">*</span>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">支持的操作系统：</td>
                                            <td class="call_3"><textarea id="body" rows="2" cols="13" id="systems" name="systems"></textarea><span style="color:#c00">*</span></td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">上传程序：</td>
                                            <td class="call_3"><?php
												echo $this->Form->input('images_url.',
												array(
													'type' => 'file', 'multiple',
													'label' => false,
													'div' => false,
													'class' => false
												)
												);?>
												<span style="color:#c00">*</span> (支持ZIP，RAR，7Z，TAR,IPA,CER,APK)
											</td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">更新内容：</td>
                                            <td class="call_3"><textarea id="body" rows="3" cols="20"  id="body" name="body"></textarea><span style="color:#c00">*</span></td>
                                    </tr>
              	</table> <br />
            	<?php echo $this->Form->submit('保存',array('label'=>false,'id'=>'pmsave', 'type'=>'submit','div'=>false,'class'=>'Button'))?><a href="index" class="Button">取消</a>
      </div>
        <script type="text/javascript" charset="utf-8">
            $('#isCheck').click(function(){
                    $('#div_text').toggle();
					if($('#isCheck').val()==1){
						input=top.document.getElementsByName("isCheck");
						input[0].value="0";
					}else{
						input=top.document.getElementsByName("isCheck");
						input[0].value="1";

					}
            });


				function funcChina(){
						var name=$("#name").val();
						if(name==""){
							alert("请填写程序名称！");
							return false;
						}
						var version=$("#version").val();
						if(version==""){
							alert("请填写版本号！");
							return false;
						}
						var SoftwareSoftId=$("#SoftwareSoftId").val();
						if(SoftwareSoftId==""){
							alert("请选择版本类别！");
							return false;
						}
						var systems=$("#systems").val();
						if(systems==""){
							alert("请填写支持的操作系统！");
							return false;
						}
						var body=$("#body").val();
						if(body==""){
							alert("请填写更新内容！");
							return false;
						}
						return true;

					}

				function  showDiv(){
                     var id=$("#SoftwarePsoftId").val();
                     $("#SoftwareSoftId").attr("disabled","disabled"); 
                     $.ajax({
                         type: "POST",
                         url: "/Softwares/ajax",
                         data: {id:id},
                         success: function(data){
                                    for(var i=0;i<SoftwareSoftId.options.length;i++){
                                            if(data==SoftwareSoftId.options[i].value){
                                                SoftwareSoftId.options[i].selected = true;
                                                return;
                                            }
                                        }
                                      
                                    // $("#SoftwareSoftId").attr("readonly","readonly");
                                  }

                     });
                }



		</script>
