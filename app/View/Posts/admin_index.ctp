 <div style="padding-bottom:10px">
                                    <a href="add" class="Button">添加</a>
                            </div>         
                            <div class="call">
                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                    <thead>
                                        <tr>
                                            <th>资料名称</th>
                                            <th>资料类别</th>
                                            <th>文档类别</th>
                                            <th>更新日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($Postlist as $val){?>
                                        <tr class="odd_gradeC">
                                            <td><?php echo $val['posts']['title']?></td>
											<?php 
											//pr($val['posts']['type']);
											if($val['posts']['type'] <=3){?>
                                             <td><?php echo $catelist[$val['posts']['type']];?></td>
											<?php }else{?>
											<td><?php echo $softlist[$val['posts']['type']];?></td>
											 
											  <?php }?>
                                            <td><?php echo $val['attachments']['type']?></td>
                                            <td><?php echo $val['posts']['created']?></td>
                                            <td>
                                             <a href="edit/<?php echo $val['posts']['id'];?>" title="编辑"><img src="/img/a_3.png" /></a>
											 <a href="delete/<?php echo $val['posts']['id'];?>" title="删除"><img src="/img/a_2.png" /></a>
											
                                            </td>
                                        </tr>
                                      <?php }?>
                                       
                                      
                                    </tbody>
                                </table>

                            
  </div>
