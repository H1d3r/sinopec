  <div class="call">
   <?php echo $this->Form->create('Post',array('onSubmit'=>'return funcChina();','div'=>false,'name'=>'frm','enctype' => 'multipart/form-data','action'=>'pmsave')); ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                            <td class="call_1">是否为程序更新：</td>
                                            <td class="call_3"><input type="checkbox" name="isCheck" id="isCheck" value="0"></td>
                                    </tr>

                                    <tr id="div_text" style="display: none;">
                                            <td class="call_1">需要更新的程序：</td>
                                            <td class="call_3">
                                                <select class="pjad" >
                                                    <option>-- 请选择要更新的程序 --</option>
                                                    <option>iPad端二期通用版</option>
                                                    <option>iPad端二期专用版</option>
                                                    <option>运行环境</option>
                                                </select>
                                                <span style="color:#c00">*</span>
                                            </td>
                                    </tr>

                                    <tr>
                                            <td class="call_1">程序名称：</td>
                                            <td class="call_3"><input type="text" class="pjad" /><span style="color:#c00">*</span></td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">版本号：</td>
                                            <td class="call_3"><input type="text" class="pjad" /><span style="color:#c00">*</span></td>
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
                                            <td class="call_3"><textarea id="body" rows="2" cols="13" name="body"></textarea><span style="color:#c00">*</span></td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">上传程序：</td>
                                            <td class="call_3"><?php
												echo $this->Form->input('images_url.',
												array(
												'type' => 'file', 'multiple'
												)	
												);?>
												<span style="color:#c00">*</span> (支持ZIP，RAR，7Z，TAR)
											</td>
                                    </tr>                    
                                    <tr>
                                            <td class="call_1">更新内容：</td>
                                            <td class="call_3"><textarea id="body" rows="3" cols="20" name="body"></textarea><span style="color:#c00">*</span></td>
                                    </tr>                    
              	</table> <br /> 	
            	<?php echo $this->Form->submit('保存',array('label'=>false,'id'=>'msave', 'type'=>'submit','div'=>false,'class'=>'Button'))?><a href="index" class="Button">取消</a>     

                            
                            </div>
        <script type="text/javascript" charset="utf-8">
            $('#isCheck').click(function(){
                    $('#div_text').toggle();
            });
                                            
     
		</script>
