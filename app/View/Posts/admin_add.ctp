<?php
        echo $this->Html->script(array('Posts/know.js'));			
?>	
                            <div class="call">
							 <?php echo $this->Form->create('Post',array('onSubmit'=>'return funcChina();','div'=>false,'name'=>'frm','enctype' => 'multipart/form-data','action'=>'msave')); ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                    <tr>
                                            <td class="call_1">文档名称：</td>
                                            <td class="call_3"><input type="text" class="pjad" id="title" name="title"/><span style="color:#c00">*</span></td>
                                    </tr>
									<tr>
                                            <td class="call_1">文档分类：</td>
                                            <td class="call_3">
																			
												<?php
													echo $this->Form->input('doc_id',
														array(
														'type'	=> 'select',
														'label' => false,
														'div' => false,
														'class' => false,
														'empty' => '——请选择——',
														'options' => $doclist
													)	
												);
																
												?>
												<span id="KnowledgesfessionsId">
													 <?php
														echo $this->Form->input('cate_id',
															array(
															'type'	=> 'select',
															'label' => false,
															'div' => false,
															'class' => false,
															'id' => 'cate_id',
															'name'=>"data[cate_id]",
															'empty' => '——请选择——'
															
														)	
													);
																	
													?>
				</span>
                <span style="color:#f00;font-weight:bold">*</span>	
											   
											   
											   
											   
											   
                                                <span style="color:#c00">*</span>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td class="call_1">上传文档：</td>
                                            <td class="call_3">	
									<?php
										echo $this->Form->input('images_url.',
										array(
										'type' => 'file', 'multiple',
										'label' => false,
										'div' => false,
										'class' => false
										)	
										);?><span style="color:#c00">*</span> (支持FDP，DOC，XLS，PPT)</td>
                                    </tr>                    
                                    <tr>
                                            <td class="call_1">文档描述：</td>
                                            <td class="call_3"><textarea id="body" rows="3" cols="20" name="body"></textarea></td>
                                    </tr>                    
              	</table> <br /> 	
            	<?php echo $this->Form->submit('保存',array('label'=>false,'id'=>'msave', 'type'=>'submit','div'=>false,'class'=>'Button'))?><a href="sourceindex" class="Button">取消</a>   
				</div>
				<script>
				function funcChina(){
						var name=$("#title").val();
						if(name==""){
							alert("请填写文档名称");
							return false;
						}
						var cateid=$("#PostCateId").val();
						if(cateid==""){
							alert("请选择文档分类");
							return false;
						}
						var docid=$("#PostDocId").val();
						if(docid==""){
							alert("请选择文档归类");
							return false;
						}
						
						return true;
					
					}
				
				
				
				</script>