﻿    <div style="padding-bottom:10px">
                                    <a href="add" class="Button">添加</a>
                            </div>         
                            <div class="call">
                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                    <thead>
                                        <tr>
                                            <th>程序名称</th>
                                            <th>版本号</th>
                                            <th>版本类别</th>
                                            <th>更新日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($relist as $val){?>
                                        <tr class="odd_gradeC">
                                            <td><?php echo $val['Software']['name']?></td>
                                            <td><?php echo $val['Software']['version']?></td>
                                            <td><?php echo $slist[$val['Software']['type']];?></td>
                                            <!--<td><?php echo date("Y-m-d",strtotime($val['Software']['created']))?></td>-->
											<td><?php echo $val['Software']['created'];?></td>
                                            <td>
												<?php if($val['Attachment']['type']== 'ipa'){?>
												  <a href="iosedit/<?php echo $val['Software']['id'];?>" title="编辑"><img src="/img/a_3.png" /></a>
												<?php }else{?>
                                                <a href="edit/<?php echo $val['Software']['id'];?>" title="编辑"><img src="/img/a_3.png" /></a>
												<?php }?>
												 <a href="delete/<?php echo $val['Software']['id'];?>" title="删除"><img src="/img/a_2.png" /></a>
                                            </td>
                                        </tr>
                                    <?php }?>   
                                    </tbody>
                                </table>

                            
                            </div>
