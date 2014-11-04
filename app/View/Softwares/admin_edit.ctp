  <div class="call">
   <?php echo $this->Form->create('Software',array('onSubmit'=>'return funcChina();','div'=>false,'name'=>'frm','enctype' => 'multipart/form-data','action'=>'pmsave')); ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <!-- <tr>
                                            <td class="call_1">是否为程序更新：</td>
											
											<?php if($Softlist[0]['softwares']['parent_id']!=0){?>	
												 <td class="call_3"><input type="checkbox"  value="0"  id="isCheck" name="isCheck" checked="checked" /></td>
												<input type="hidden"  value="0" id="hi" name="hi" />
											<?php }else{?>
												 <td class="call_3"><input type="checkbox"  id="isCheck" value="1"  name="isCheck"></td>
											<?php }?>
                                           
											
                                    </tr>
								<?php if($Softlist[0]['softwares']['parent_id']!=0){?>	
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
														'selected'=>$Softlist1[0]['softwares']['id'],
														'options' => $lists
														)	
											   );?>
                                                <span style="color:#c00">*</span>
                                            </td>
                                    </tr>
									<?php }else{?>
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
														'options' => $lists
														)	
											   );?>
                                                <span style="color:#c00">*</span>
                                            </td>
									<?php }?>-->
                                    <tr>
                                            <td class="call_1">程序名称：</td>
                                            <td class="call_3"><input type="text" class="pjad" id="name" name="name" value="<?php echo $Softlist[0]['softwares']['name'];?>"/><span style="color:#c00">*</span></td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">版本号：</td>
                                            <td class="call_3"><input type="text" class="pjad" id="version"  name="version" value="<?php echo $Softlist[0]['softwares']['version'];?>"/><span style="color:#c00">*</span></td>
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
														'selected'=>$Softlist[0]['softwares']['type'],
														'options' => $soft
														)	
											   );?>
                                                <span style="color:#c00">*</span>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">支持的操作系统：</td>
                                            <td class="call_3"><textarea id="body" rows="2" cols="13" id="systems" name="systems"><?php echo $Softlist[0]['softwares']['systems'];?></textarea><span style="color:#c00">*</span></td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">上传程序：</td>
                                            <td class="call_3"><input type="file" name="data[Software][images_url][]">如果不修改则留空 (支持ZIP，RAR，7Z，TAR,IPA,CER,APK)
											</td>
                                    </tr>                    
                                    <tr>
                                            <td class="call_1">更新内容：</td>
                                            <td class="call_3"><textarea id="body" rows="3" cols="20"  id="body" name="body"><?php echo $Softlist[0]['softwares']['body'];?></textarea><span style="color:#c00">*</span></td>
                                    </tr>    		
              	</table> <br /> 
					<input type="hidden" name="id" value="<?php echo $id;?>">				
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
			//修改页面的是否有合同的显示
		if($('#isCheck').val()==0){
			$('#div_text').toggle();
		}
                                            
		
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
				
				
				
				
		</script>
